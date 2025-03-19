function showPopup() {
    document.getElementById("confirmationPopup").style.display = "flex";
}

function hidePopup() {
    document.getElementById("confirmationPopup").style.display = "none";
}

function submitForm() {
    document.querySelector("form").submit();
}

function closePopup() {
    document.getElementById("phone-popup").style.display = "none";
}
