const contentArea = document.querySelector(".content-area");

if (contentArea) {
    const autoResize = () => {
        contentArea.style.height = "auto";
        contentArea.style.height = contentArea.scrollHeight + "px";
    };
    contentArea.addEventListener("input", autoResize);
    window.addEventListener("load", autoResize);
}

const pageForm = document.getElementById("pageForm");
const saveStatus = document.getElementById("saveStatus");

if (pageForm && saveStatus) {
    pageForm.addEventListener("input", () => {
        saveStatus.textContent = "Unsaved changes...";
        saveStatus.style.color = "#f5c542";
    });

    pageForm.addEventListener("submit", () => {
        saveStatus.textContent = "Saving...";
        saveStatus.style.color = "#7ee787";
    });
}
