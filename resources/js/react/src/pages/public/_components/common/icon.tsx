export const CartIcon = ({ className = "size-5" }: { className?: string }) => {
    return (
        <svg
            className={className}
            viewBox="0 0 21 20"
            stroke="currentColor"
            strokeWidth="1.5"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
        >
            <path
                strokeLinecap="round"
                strokeLinejoin="round"
                d="M1.13281 0.833547L1.54948 0.833496V0.833496C2.78264 0.833526 3.86637 1.65101 4.20515 2.83672L4.3471 3.33355M4.3471 3.33355L5.63992 7.85843C6.11531 9.5223 6.35301 10.3542 6.83827 10.9717C7.26659 11.5168 7.82919 11.9412 8.47093 12.2033C9.19799 12.5002 10.0632 12.5002 11.7937 12.5002H12.8091C13.8588 12.5002 14.3837 12.5002 14.8433 12.39C15.9407 12.127 16.8759 11.4127 17.4184 10.4232C17.6456 10.0087 17.7837 9.50235 18.0599 8.4896V8.4896C18.3964 7.2559 18.5646 6.63905 18.5321 6.13859C18.4535 4.93171 17.6578 3.89005 16.5142 3.49667C16.0399 3.33355 15.4005 3.33355 14.1218 3.33355H4.3471ZM10.2995 16.6668C10.2995 17.5873 9.55329 18.3335 8.63281 18.3335C7.71234 18.3335 6.96615 17.5873 6.96615 16.6668C6.96615 15.7464 7.71234 15.0002 8.63281 15.0002C9.55329 15.0002 10.2995 15.7464 10.2995 16.6668ZM16.9661 16.6668C16.9661 17.5873 16.22 18.3335 15.2995 18.3335C14.379 18.3335 13.6328 17.5873 13.6328 16.6668C13.6328 15.7464 14.379 15.0002 15.2995 15.0002C16.22 15.0002 16.9661 15.7464 16.9661 16.6668Z"
            />
        </svg>
    );
};
export const CustomSearchIcon = ({
    className = "size-5",
    type = "footer",
}: {
    className?: string;
    type?: "header" | "footer";
}) => {
    return (
        <svg
            className={className}
            viewBox="0 0 20 20"
            stroke="currentColor"
            strokeWidth={type === "header" ? "1.2" : "1.5"}
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
        >
            <path
                strokeLinecap="round"
                strokeLinejoin="round"
                d="M18.4007 17.4998L15.3707 14.4698M15.3707 14.4698C16.7279 13.1126 17.5674 11.2376 17.5674 9.1665C17.5674 5.02437 14.2095 1.6665 10.0674 1.6665C5.92525 1.6665 2.56738 5.02437 2.56738 9.1665C2.56738 13.3086 5.92525 16.6665 10.0674 16.6665C12.1385 16.6665 14.0135 15.827 15.3707 14.4698Z"
            />
        </svg>
    );
};

export const CustomUserIcon = ({
    className = "size-5",
    strokeWidth = "1.5",
}: {
    className?: string;
    strokeWidth?: string;
}) => {
    return (
        <svg
            className={className}
            viewBox="0 0 24 24"
            strokeWidth={strokeWidth}
            stroke="currentColor"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
            role="presentation"
        >
            <rect width="10.5" height="10.5" x="6.75" y="1.75" rx="5.25"></rect>
            <path
                strokeLinecap="round"
                d="M12 15.5c1.5 0 4 .333 4.5.5.5.167 3.7.8 4.5 2 1 1.5 1 2 1 4m-10-6.5c-1.5 0-4 .333-4.5.5-.5.167-3.7.8-4.5 2-1 1.5-1 2-1 4"
            ></path>
        </svg>
    );
};
