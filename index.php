<?php include 'auth.php'; ?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Dosya Yöneticisi</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script>
    tailwind.config = {
      darkMode: 'class',
      theme: {
        extend: {
          colors: {
            primary: '#5D5CDE'
          }
        }
      }
    }
  </script>
  <style>
    .upload-area.dragover {
      border-color: #5D5CDE;
      background-color: rgba(93, 92, 222, 0.1);
    }
  </style>
</head>
<body class="bg-white dark:bg-[#181818] min-h-screen transition-colors duration-200">
  <div class="container mx-auto px-4 py-8 max-w-4xl">
    <div class="text-center mb-8">
      <h1 class="text-4xl font-bold text-gray-800 dark:text-white mb-2">
        <i class="fas fa-cloud-upload-alt text-primary"></i> Dosya Yöneticisi
      </h1>
      <p class="text-gray-600 dark:text-gray-400">Dosyalarınızı yükleyin, görüntüleyin ve yönetin</p>
    </div>

    <!-- Upload -->
    <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-6 mb-8 shadow-lg">
      <div id="uploadArea" class="upload-area border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-lg p-8 text-center cursor-pointer">
        <i class="fas fa-cloud-upload-alt text-6xl text-gray-400 dark:text-gray-600 mb-4"></i>
        <p class="text-gray-700 dark:text-gray-300 mb-2 text-lg">Dosyaları buraya sürükleyin veya tıklayın</p>
        <input type="file" id="fileInput" class="hidden" multiple>
      </div>
      <div id="uploadProgress" class="mt-4 hidden">
        <div class="bg-gray-200 dark:bg-gray-700 rounded-full h-2 overflow-hidden">
          <div id="progressBar" class="bg-primary h-full transition-all duration-300" style="width: 0%"></div>
        </div>
        <p id="uploadStatus" class="text-sm text-gray-600 dark:text-gray-400 mt-2 text-center"></p>
      </div>
    </div>

    <!-- Filter -->
    <div class="flex justify-between items-center mb-4">
      <input type="text" id="searchInput" class="form-input w-full max-w-sm" placeholder="Dosya ara...">
      <select id="filterSelect" class="form-select ml-4">
        <option value="">Tüm Türler</option>
        <option value="jpg">JPG</option>
        <option value="png">PNG</option>
        <option value="pdf">PDF</option>
        <option value="docx">DOCX</option>
        <option value="xlsx">XLSX</option>
      </select>
    </div>

    <!-- File List -->
    <div id="fileList" class="space-y-4"></div>
  </div>

  <script>
    let currentPath = '';
    let selectedFiles = [];

    const uploadArea = document.getElementById('uploadArea');
    const fileInput = document.getElementById('fileInput');
    const progressBar = document.getElementById('progressBar');
    const uploadStatus = document.getElementById('uploadStatus');
    const uploadProgress = document.getElementById('uploadProgress');
    const fileList = document.getElementById('fileList');
    const searchInput = document.getElementById('searchInput');
    const filterSelect = document.getElementById('filterSelect');

    uploadArea.addEventListener('click', () => fileInput.click());
    uploadArea.addEventListener('dragover', e => { e.preventDefault(); uploadArea.classList.add('dragover'); });
    uploadArea.addEventListener('dragleave', () => uploadArea.classList.remove('dragover'));
    uploadArea.addEventListener('drop', e => {
      e.preventDefault(); uploadArea.classList.remove('dragover');
      uploadFiles(e.dataTransfer.files);
    });
    fileInput.addEventListener('change', () => uploadFiles(fileInput.files));

    function uploadFiles(files) {
      if (!files.length) return;
      uploadProgress.classList.remove('hidden');
      const formData = new FormData();
      for (let file of files) formData.append('files[]', file);
      formData.append('path', currentPath);
      fetch('upload.php', { method: 'POST', body: formData })
        .then(() => {
          uploadStatus.textContent = `${files.length} dosya yüklendi`;
          progressBar.style.width = '100%';
          setTimeout(() => {
            uploadProgress.classList.add('hidden');
            progressBar.style.width = '0%';
            loadFiles();
          }, 1500);
        });
    }

    function loadFiles() {
      const filter = filterSelect.value;
      fetch(`browse.php?path=${encodeURIComponent(currentPath)}&filter=${filter}`)
        .then(res => res.json())
        .then(data => {
          fileList.innerHTML = '';
          data.forEach(item => {
            const match = item.name.toLowerCase().includes(searchInput.value.toLowerCase());
            if (!match) return;

            const row = document.createElement('div');
            row.className = 'bg-white dark:bg-gray-800 p-4 rounded-lg shadow flex justify-between items-center';

            let preview = '';
            if (item.type.startsWith('image')) {
              preview = `<img src="preview.php?path=${currentPath}&file=${item.name}" class="w-16 h-16 rounded mr-4">`;
            } else if (item.type === 'application/pdf') {
              preview = `<iframe src="preview.php?path=${currentPath}&file=${item.name}" class="w-16 h-16 mr-4"></iframe>`;
            }

            row.innerHTML = `
              <div class="flex items-center">
                ${preview}
                <div>
                  <p class="text-gray-800 dark:text-white font-semibold">${item.name}</p>
                  <p class="text-sm text-gray-500">${item.size}</p>
                </div>
              </div>
              <div class="flex space-x-2">
                <a href="download.php?path=${currentPath}&file=${item.name}" class="text-primary"><i class="fas fa-download"></i></a>
                <button onclick="renameFile('${item.name}')" class="text-yellow-500"><i class="fas fa-edit"></i></button>
                <button onclick="deleteFile('${item.name}')" class="text-red-500"><i class="fas fa-trash"></i></button>
              </div>
            `;
            fileList.appendChild(row);
          });
        });
    }

    function deleteFile(name) {
      fetch('delete.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `name=${encodeURIComponent(name)}&path=${encodeURIComponent(currentPath)}`
      }).then(() => loadFiles());
    }

    function renameFile(oldName) {
      const newName = prompt("Yeni dosya adı:", oldName);
      if (!newName || newName === oldName) return;
      fetch('rename.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `old=${encodeURIComponent(oldName)}&new=${encodeURIComponent(newName)}&path=${encodeURIComponent(currentPath)}`
      }).then(() => loadFiles());
    }

    searchInput.addEventListener('input', loadFiles);
    filterSelect.addEventListener('change', loadFiles);
    loadFiles();
  </script>
</body>
</html>
