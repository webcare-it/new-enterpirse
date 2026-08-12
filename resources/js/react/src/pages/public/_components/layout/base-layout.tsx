import { Footer } from "./footer";
import { Header } from "./header";
import { TopBar } from "./header/top-bar";

interface Props {
    children: React.ReactNode;
}

export const BaseLayout = ({ children }: Props) => {
    return (
        <section className="min-h-screen flex flex-col">
            <TopBar />
            <Header />
            {children}
            <Footer />
        </section>
    );
};

export const LayoutContainer = ({
    children,
    className = "",
}: {
    children: React.ReactNode;
    className?: string;
}) => {
    return (
        <section className={`w-[95%] mx-auto ${className}`}>{children}</section>
    );
};
