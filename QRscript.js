// Get the user input and options
const userInput = document.getElementById("userInput");
const sizeOptions = document.querySelector(".sizeOptions");
const BGColor = document.getElementById("BGColor");
const FGColor = document.getElementById("FGColor");
const generateBtn = document.getElementById("submit");
const downloadBtn = document.getElementById("download");

// Get the container for the QR code
const container = document.querySelector(".container");

// Function to generate the QR code
function generateQRCode() {
  // Get the size, color options
  const size = sizeOptions.value;
  const bgColor = BGColor.value;
  const fgColor = FGColor.value;

  // Check if the user input is empty
  if (userInput.value.trim() === "") {
    container.innerHTML = "<p>Please enter some text or a URL.</p>";
    return;
  }

  // Generate the QR code
  const qrcode = new QRCode(container, {
    width: size,
    height: size,
    colorDark: fgColor,
    colorLight: bgColor,
    correctLevel: QRCode.CorrectLevel.H
  });

  // Set the download link to the QR code image
  const qrImg = container.querySelector("img");
  const downloadLink = document.createElement("a");
  downloadLink.innerHTML = "Download";
  downloadLink.download = "QRCode.png";
  downloadLink.href = qrImg.src;
  downloadLink.style.display = "none";
  downloadBtn.parentNode.replaceChild(downloadLink, downloadBtn);
  downloadLink.click();
}

// Add event listeners
userInput.addEventListener("input", () => {
  generateBtn.disabled = false;
  container.innerHTML = "";
  downloadBtn.style.display = "none";
});

generateBtn.addEventListener("click", generateQRCode);
