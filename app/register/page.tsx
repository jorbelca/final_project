import EmcaLogo from "@/app/ui/emca-logo";
import RegisterForm from "@/app/ui/register-form";
import { Metadata } from "next";

export const metadata: Metadata = {
  title: "Register",
};

export default function RegisterPage() {
  return (
    <main className="flex items-center justify-center md:h-screen bg-green-200">
      <div className="relative mx-auto flex w-full max-w-[400px] flex-col space-y-2.5 p-4 md:-mt-32">
        <div className="flex h-20 w-full items-center justify-center rounded-lg bg-violet-400 p-3 md:h-36">
          <div className="w-320 text-white md:w-360">
            <EmcaLogo />
          </div>
        </div>
        <RegisterForm />
      </div>
    </main>
  );
}
