import { pipeline } from "@huggingface/transformers";

let mediaRecorder;
let transcriber = null;
let quality;

const qualityOptions = {
    low: [
        "automatic-speech-recognition",
        "onnx-community/whisper-tiny",
        { dtype: "q4", device: "auto" },
    ],
    medium: [
        "automatic-speech-recognition",
        "Xenova/whisper-small",
        { device: "auto" },
    ],
    high:[
        "automatic-speech-recognition",
        "Xenova/whisper-medium",
        { device: "auto" },
    ],
};

export async function start(selectedQuality) {
    quality = selectedQuality;

    const stream = await getAudioStream();
    const audioBlob = await recordAudio(stream);
    const text = await transcribeAudio(audioBlob);
    return { success: true, transcription: text };
}

export function stop() {
    if (mediaRecorder) {
        mediaRecorder.stop();
        mediaRecorder.stream.getTracks().forEach((t) => t.stop());
    }
    mediaRecorder = null;
}

async function getAudioStream() {
    return navigator.mediaDevices.getUserMedia({ audio: true });
}

function recordAudio(stream) {
    return new Promise((resolve) => {
        const chunks = [];
        const recorder = new MediaRecorder(stream);

        recorder.ondataavailable = (e) => chunks.push(e.data);

        recorder.onstop = () => {
            const audioBlob = new Blob(chunks, { type: "audio/wav" });
            chunks.length = 0;
            resolve(audioBlob);
        };

        recorder.start();
        mediaRecorder = recorder;
    });
}

async function transcribeAudio(audioBlob) {
    const url = URL.createObjectURL(audioBlob);

    await loadModel();

    const result = await transcriber(url, {
        language: "es",
        task: "transcribe",
        chunk_length_s: 30,
        stride_length_s: 5,
    });

    return result.text;
}

async function loadModel() {
    if (!transcriber) {
        const timeout = 120000;
        const timeoutPromise = new Promise((_, reject) =>
            setTimeout(() => reject(new Error("Timeout")), timeout)
        );

        try {
            transcriber = await Promise.race([
                pipeline(...qualityOptions[quality]),
                timeoutPromise,
            ]);
        } catch (error) {
            throw error;
        }
    }
}
