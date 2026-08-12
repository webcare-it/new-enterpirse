import "./html.css";

export const RenderHtml = ({ html = "" }: { html: string }) => {
    return (
        <div
            className="html_content"
            dangerouslySetInnerHTML={{ __html: html }}
        />
    );
};
