"use client";

export default function ImagePicker({ handleBodyChange }) {
  const handleImageUpload = (event) => {
    const file = event.target.files[0];

    if (file) {
      const reader = new FileReader();

      reader.onload = (e) => {
        const imageDataUrl = e.target.result;

        // Append the image to the contentEditable div
        document.execCommand("insertImage", false, imageDataUrl);
        handleBodyChange();
      };

      reader.readAsDataURL(file);
    }
  };
  return (
    <button>
      <input
        id="addImage"
        type="file"
        accept="image/*"
        onChange={handleImageUpload}
        style={{ display: "none" }}
      />
      <label htmlFor="addImage">
        <img src="/add_photo_alternate_FILL0_wght300_GRAD0_opsz24.svg" />
      </label>
    </button>
  );
}
