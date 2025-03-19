document.addEventListener("DOMContentLoaded", function () {
    const input = document.getElementById("profile_photo");
    const preview = document.getElementById("preview");
    let cropper;

    input.addEventListener("change", function (event) {
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.style.display = "block";

                if (cropper) {
                    cropper.destroy();
                }

                cropper = new Cropper(preview, {
                    aspectRatio: 1, // Cadre carré 1:1
                    viewMode: 1,
                    autoCropArea: 1,
                });
            };
            reader.readAsDataURL(file);
        }
    });

    document.getElementById("uploadForm").addEventListener("submit", function (e) {
        e.preventDefault();
        
        if (cropper) {
            const canvas = cropper.getCroppedCanvas({
                width: 626,
                height: 626,
            });

            document.getElementById("cropped_image").value = canvas.toDataURL("image/png");
            this.submit(); // Envoie le formulaire après l'ajout de l'image recadrée
        }
    });
});