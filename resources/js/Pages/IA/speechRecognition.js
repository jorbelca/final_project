import { pipeline } from "@huggingface/transformers";

let mediaRecorder;

export async function start() {
    const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
    const chunks = [];

    mediaRecorder = new MediaRecorder(stream);

    mediaRecorder.ondataavailable = (e) => chunks.push(e.data);

    return new Promise((resolve) => {
        mediaRecorder.onstop = async () => {
            const audio = new Blob(chunks, { type: "audio/wav" });
            const url = URL.createObjectURL(audio);

            let transcriber = null;
            const timeout = 120000; // 120 seconds in milliseconds

            const timeoutPromise = new Promise((_, reject) =>
                setTimeout(() => reject(new Error("Timeout")), timeout)
            );

            try {
                // Race the model loading against the timeout
                transcriber = await Promise.race([
                    pipeline(
                        "automatic-speech-recognition",
                        "onnx-community/whisper-tiny",
                        { dtype: "q4", device: "auto" }
                    ),
                    timeoutPromise
                ]);
            } catch (error) {
                if (error.message === "Timeout") {
                    console.error(`Model loading timed out after ${timeout / 1000} seconds.`);
                    resolve(false); // Resolve the outer promise with false
                    return; // Exit the onstop handler
                } else {
                    // Handle other potential errors from pipeline()
                    console.error("Error loading transcriber model:", error);
                    resolve(false); // Resolve with false on other errors too
                    return; // Exit the onstop handler
                }
            }

            // If transcriber is null here, it means something unexpected happened,
            // but the try/catch should have handled errors/timeout.
            // If it's not null, proceed with transcription.
            if (!transcriber) {
                 console.error("Transcriber initialization failed unexpectedly.");
                 resolve(false);
                 return;
            }
            const result = await transcriber(url, {
                language: "es",
                task: "transcribe",
                chunk_length_s: 30,
                stride_length_s: 5,
            });
            resolve(result.text);
        };
        mediaRecorder.start();
    });
}

export function stop() {
    mediaRecorder.stop();
    mediaRecorder.stream.getTracks().forEach((t) => t.stop());
}
