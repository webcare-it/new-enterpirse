import { useConfig } from "@/hooks/useConfig";

export const SocialMessage = ({
    type = "layout",
    link,
}: {
    type?: string;
    link?: string;
}) => {
    const config = useConfig();
    const whatsappNumber = config?.whatsapp_number as string;

    if (type === "details") {
        return (
            <div className="flex flex-col gap-4">
                <a
                    href={`https://api.whatsapp.com/send?phone=${whatsappNumber}?text=${link}`}
                    target="_blank"
                    rel="noreferrer"
                    className="w-full"
                >
                    <div className="rounded-3xl w-full transition-all duration-300 cursor-pointer h-10 md:h-12 flex items-center justify-center bg-green-600/80 text-white hover:bg-green-600 border text-sm md:text-base border-green-600 gap-1 md:gap-2">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 32 32"
                            className="size-5 md:size-6 fill-white"
                        >
                            <path d="M16 .396C7.163.396 0 7.56 0 16.396c0 2.887.755 5.703 2.188 8.182L0 32l7.627-2.142A15.93 15.93 0 0016 32c8.837 0 16-7.163 16-16.004C32 7.56 24.837.396 16 .396zm0 29.227c-2.54 0-5.032-.678-7.203-1.964l-.516-.305-4.525 1.272 1.206-4.41-.336-.539A13.92 13.92 0 012.08 16.396c0-7.682 6.244-13.926 13.92-13.926 7.676 0 13.92 6.244 13.92 13.926 0 7.683-6.244 13.927-13.92 13.927zm7.69-10.44c-.42-.21-2.48-1.224-2.863-1.363-.382-.14-.66-.21-.938.21-.28.42-1.08 1.363-1.324 1.645-.243.28-.487.315-.907.105-.42-.21-1.774-.654-3.377-2.087-1.247-1.114-2.088-2.49-2.333-2.91-.243-.42-.026-.647.184-.856.19-.19.42-.487.63-.73.21-.244.28-.42.42-.7.14-.28.07-.525-.035-.735-.105-.21-.938-2.26-1.284-3.09-.337-.807-.68-.7-.938-.714l-.8-.014c-.28 0-.735.105-1.12.525-.383.42-1.47 1.436-1.47 3.5s1.506 4.06 1.716 4.34c.21.28 2.967 4.533 7.195 6.356 1.006.435 1.79.695 2.4.89 1.008.32 1.926.275 2.65.167.808-.12 2.48-1.014 2.83-1.994.35-.98.35-1.82.245-1.995-.105-.175-.385-.28-.805-.49z" />
                        </svg>
                        Order via Whatsapp
                    </div>
                </a>
            </div>
        );
    }

    if (type === "layout")
        return (
            <a
                href={`https://api.whatsapp.com/send?phone=${whatsappNumber}?text=${window.location.href}`}
                target="_blank"
                className="fixed right-2 md:right-4 bottom-16 md:bottom-24 z-50"
            >
                <div className="relative flex items-center justify-center">
                    <span className="absolute inline-flex size-10 md:size-14 rounded-full bg-green-500 opacity-75 animate-ping"></span>
                    <span className="absolute inline-flex size-12 md:size-16 rounded-full bg-green-500 opacity-50 animate-ping delay-[4000ms]"></span>

                    <div className="relative size-10 md:size-14 rounded-full bg-green-500 flex items-center justify-center shadow-lg hover:scale-110 transition">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 32 32"
                            className="size-5 md:size-7 fill-white"
                        >
                            <path d="M16 .396C7.163.396 0 7.56 0 16.396c0 2.887.755 5.703 2.188 8.182L0 32l7.627-2.142A15.93 15.93 0 0016 32c8.837 0 16-7.163 16-16.004C32 7.56 24.837.396 16 .396zm0 29.227c-2.54 0-5.032-.678-7.203-1.964l-.516-.305-4.525 1.272 1.206-4.41-.336-.539A13.92 13.92 0 012.08 16.396c0-7.682 6.244-13.926 13.92-13.926 7.676 0 13.92 6.244 13.92 13.926 0 7.683-6.244 13.927-13.92 13.927zm7.69-10.44c-.42-.21-2.48-1.224-2.863-1.363-.382-.14-.66-.21-.938.21-.28.42-1.08 1.363-1.324 1.645-.243.28-.487.315-.907.105-.42-.21-1.774-.654-3.377-2.087-1.247-1.114-2.088-2.49-2.333-2.91-.243-.42-.026-.647.184-.856.19-.19.42-.487.63-.73.21-.244.28-.42.42-.7.14-.28.07-.525-.035-.735-.105-.21-.938-2.26-1.284-3.09-.337-.807-.68-.7-.938-.714l-.8-.014c-.28 0-.735.105-1.12.525-.383.42-1.47 1.436-1.47 3.5s1.506 4.06 1.716 4.34c.21.28 2.967 4.533 7.195 6.356 1.006.435 1.79.695 2.4.89 1.008.32 1.926.275 2.65.167.808-.12 2.48-1.014 2.83-1.994.35-.98.35-1.82.245-1.995-.105-.175-.385-.28-.805-.49z" />
                        </svg>
                    </div>
                </div>
            </a>
        );
};
