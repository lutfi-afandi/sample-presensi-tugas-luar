let currentStep = 1;
let faceScanner; // Untuk menampung interval

// 1. Load Model
Promise.all([faceapi.nets.tinyFaceDetector.loadFromUri("assets/models")]).then(
  startVideo,
);

function startVideo() {
  navigator.mediaDevices
    .getUserMedia({ video: { width: 1280, height: 720 } })
    .then((stream) => {
      document.getElementById("video").srcObject = stream;
    })
    .catch((err) => alert("Kamera Error: " + err));
}

// 2. Logika Utama (Step 1: Deteksi Wajah)
video.addEventListener("play", () => {
  faceScanner = setInterval(async () => {
    if (currentStep === 1) {
      const detections = await faceapi.detectAllFaces(
        video,
        new faceapi.TinyFaceDetectorOptions(),
      );

      if (detections.length > 0) {
        $("#face-status")
          .text("Wajah Terdeteksi")
          .removeClass("bg-light")
          .addClass("bg-success text-white");
        $("#btn-capture-wajah").prop("disabled", false);
      } else {
        $("#face-status")
          .text("Cari Wajah...")
          .removeClass("bg-success text-white")
          .addClass("bg-light text-secondary");
        $("#btn-capture-wajah").prop("disabled", true);
      }
    }
  }, 500);
});

// 3. Aksi Klik: Ambil Foto Wajah (Pindah ke Step 2)
$("#btn-capture-wajah").click(function () {
  // Ambil Gambar
  const dataUri = captureToCanvas();
  $("#input_wajah").val(dataUri);
  $("#prev-wajah").attr("src", dataUri);

  // Update UI ke Step 2
  currentStep = 2;
  clearInterval(faceScanner); // Matikan scan wajah biar ringan & ga ganggu step 2

  $("#step-title").html(
    '<i class="bi bi-camera"></i> LANGKAH 2: FOTO KEGIATAN',
  );
  $("#step-number").text("2/3");
  $("#face-status").hide(); // Sembunyikan status deteksi wajah
  $(this).hide();
  $("#btn-capture-kegiatan").fadeIn();
});

// 4. Aksi Klik: Ambil Foto Kegiatan (Pindah ke Step 3)
$("#btn-capture-kegiatan").click(function () {
  const dataUri = captureToCanvas();
  $("#input_kegiatan").val(dataUri);
  $("#prev-kegiatan").attr("src", dataUri);

  // Matikan Kamera & Pindah ke Final Step
  const stream = video.srcObject;
  const tracks = stream.getTracks();
  tracks.forEach((track) => track.stop()); // Kamera mati

  $("#camera-section").hide();
  $("#final-section").fadeIn();
});

// Helper Fungsi Snapshot
function captureToCanvas() {
  const canvas = document.createElement("canvas");
  canvas.width = video.videoWidth;
  canvas.height = video.videoHeight;
  canvas.getContext("2d").drawImage(video, 0, 0);
  return canvas.toDataURL("image/jpeg", 0.8);
}

// Ambil GPS di latar belakang
navigator.geolocation.getCurrentPosition((p) => {
  $("#lat").val(p.coords.latitude);
  $("#long").val(p.coords.longitude);
});
