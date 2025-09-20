/**
 * 
 * @param {Event} e 
 */
async function SendImport(e) {
    e.preventDefault();
    const form = e.currentTarget;
    const send_handler = document.getElementById("send-btn")

    if (form.file.files.length === 0) {
        alert("No file attached")
        return;
    }

    const file = form.file.files[0];
    let text = (await file.text()).replaceAll("\r", "").replaceAll("\t", "");
    if (file.type === "application/json") text = text.replaceAll('\n', '');
    send_handler.disabled = true;


    // sending file contents instead of the file iteself so that i dont have to fiddle with the server settings
    const data = new FormData();
    data.append('file_content', text);
    data.append('type', file.type);

    try {
        const response = await fetch("/api/import", {
            method: 'POST',
            body: data,
        });

        if (!response.ok) {
            const resp_body = await response.json();
            alert(resp_body.msg)
        }
    } catch {

    }

    send_handler.disabled = false;
}

window.onload = function () {
    document.getElementById("file-import").addEventListener("submit", SendImport)
}