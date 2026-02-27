@extends('layouts.admin')


@section('content')

<style>
  /* ── Drop Zone ── */
  .drop-zone {
    border: 2px dashed #adb5bd;
    border-radius: 10px;
    padding: 36px 20px;
    text-align: center;
    cursor: pointer;
    transition: border-color .2s, background .2s;
    background: #f8f9fa;
    position: relative;
  }

  .drop-zone:hover,
  .drop-zone.drag-over {
    border-color: #007bff;
    background: #edf4ff;
  }

  .drop-zone.uploading {
    border-color: #ffc107;
    background: #fffbea;
    cursor: not-allowed;
  }

  .drop-zone.upload-done {
    border-color: #28a745;
    background: #f0fff4;
    cursor: default;
  }

  .drop-zone .dz-icon {
    font-size: 2.4rem;
    line-height: 1;
    margin-bottom: 8px;
  }

  .drop-zone .dz-text {
    margin: 0;
    color: #555;
    font-size: .95rem;
  }

  .drop-zone .dz-hint {
    color: #999;
    font-size: .82rem;
    margin-top: 4px;
  }

  .drop-zone .click-link {
    color: #007bff;
    text-decoration: underline;
  }

  /* ── Preview ── */
  #previewWrap img {
    width: 110px;
    height: 110px;
    object-fit: cover;
    border-radius: 8px;
    border: 2px solid #28a745;
  }

  /* ── Create button ── */
  #submitBtn:disabled {
    opacity: .45;
    cursor: not-allowed;
  }
</style>

<div class="row">
  <div class="col">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title mb-4 d-inline">Create Product</h5>

        {{-- Validation errors --}}
        @if($errors->any())
        <div class="alert alert-danger mt-3">
          <ul class="mb-0">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
        @endif

        <form id="createProductForm" method="POST" action="{{ route('store.products') }}">
          @csrf

          <div class="form-outline mb-4 mt-4">
            <input type="text" name="name" class="form-control" placeholder="Name" value="{{ old('name') }}" required />
          </div>

          <div class="form-outline mb-4 mt-4">
            <input type="text" name="price" class="form-control" placeholder="Price" value="{{ old('price') }}"
              required />
          </div>

          {{-- ── Image upload ── --}}
          <div class="form-outline mb-4 mt-2">
            <label class="form-label font-weight-bold">Product Image</label>

            {{-- Drop zone --}}
            <div id="dropZone" class="drop-zone">
              <div id="dzIdle">
                <div class="dz-icon">🖼️</div>
                <p class="dz-text">Kéo &amp; thả ảnh vào đây, hoặc <span class="click-link">click để chọn</span></p>
                <p class="dz-hint">Hỗ trợ JPG, PNG, GIF, WebP</p>
              </div>

              <div id="dzUploading" style="display:none;">
                <div class="dz-icon">⏫</div>
                <p class="dz-text mb-2">Đang upload lên Firebase...</p>
                <div class="progress mx-auto" style="height:18px; max-width:320px;">
                  <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated bg-warning"
                    role="progressbar" style="width:0%">0%</div>
                </div>
              </div>

              <div id="dzDone" style="display:none;">
                <div id="previewWrap">
                  <img id="imagePreview" src="" alt="preview">
                </div>
                <p class="dz-text mt-2 mb-1" style="color:#28a745; font-weight:600;">
                  ✔ Upload thành công!
                </p>
                <small class="text-muted" id="uploadedFileName"></small><br>
                <button type="button" id="changeImageBtn" class="btn btn-sm btn-outline-secondary mt-2">Đổi ảnh</button>
              </div>

              {{-- Real file input (hidden) --}}
              <input type="file" id="imageInput" accept="image/*" style="display:none;" />
            </div>

            <div id="uploadError" class="alert alert-danger mt-2" style="display:none;"></div>
          </div>

          {{-- Hidden: Firebase URL, filled by JS --}}
          <input type="hidden" name="image_url" id="image_url" />

          <div class="form-group">
            <label for="productDescription">Description</label>
            <textarea name="description" class="form-control" id="productDescription"
              rows="3">{{ old('description') }}</textarea>
          </div>

          <div class="form-outline mb-4 mt-4">
            <select name="type" class="form-select form-control">
              <option value="">Choose Type</option>
              <option value="drinks" {{ old('type') === 'drinks'   ? 'selected' : '' }}>drinks</option>
              <option value="desserts" {{ old('type') === 'desserts' ? 'selected' : '' }}>desserts</option>
            </select>
          </div>

          <br>

          {{-- Disabled until Firebase upload succeeds --}}
          <button type="submit" id="submitBtn" class="btn btn-primary mb-4" disabled
            title="Vui lòng upload ảnh thành công trước">
            Create
          </button>
          <span id="submitStatus" class="ml-2 text-muted" style="display:none;">Đang lưu sản phẩm...</span>
        </form>

      </div>
    </div>
  </div>
</div>

{{-- Firebase compat SDK --}}
<script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-storage-compat.js"></script>

<script>
  (function() {
    /* ── Firebase init ── */
    var storage;
    try {
      var firebaseConfig = {
        apiKey: "AIzaSyDRoIrRYsmYZmjWWDV9p-PPH6fBGTmM4_E",
        authDomain: "book-store-pegatron.firebaseapp.com",
        projectId: "book-store-pegatron",
        storageBucket: "book-store-pegatron.appspot.com",
        messagingSenderId: "649270420153",
        appId: "1:649270420133:web:57c9754daf52178c2b634c",
      };
      if (!firebase.apps.length) firebase.initializeApp(firebaseConfig);
      storage = firebase.storage();
    } catch (err) {
      console.error('Firebase init error:', err);
      showError('Firebase chưa khởi động được. Tải lại trang và thử lại.');
    }

    /* ── DOM refs ── */
    var dropZone = document.getElementById('dropZone');
    var fileInput = document.getElementById('imageInput');
    var dzIdle = document.getElementById('dzIdle');
    var dzUploading = document.getElementById('dzUploading');
    var dzDone = document.getElementById('dzDone');
    var progressBar = document.getElementById('progressBar');
    var imagePreview = document.getElementById('imagePreview');
    var uploadedName = document.getElementById('uploadedFileName');
    var imageUrlField = document.getElementById('image_url');
    var submitBtn = document.getElementById('submitBtn');
    var submitStatus = document.getElementById('submitStatus');
    var changeImageBtn = document.getElementById('changeImageBtn');
    var uploadError = document.getElementById('uploadError');

    /* ── Helpers ── */
    function showError(msg) {
      uploadError.textContent = msg;
      uploadError.style.display = 'block';
    }

    function hideError() {
      uploadError.style.display = 'none';
    }

    function setZoneState(state) {
      dropZone.classList.remove('drag-over', 'uploading', 'upload-done');
      dzIdle.style.display = 'none';
      dzUploading.style.display = 'none';
      dzDone.style.display = 'none';
      if (state === 'idle') {
        dzIdle.style.display = 'block';
      }
      if (state === 'uploading') {
        dzUploading.style.display = 'block';
        dropZone.classList.add('uploading');
      }
      if (state === 'done') {
        dzDone.style.display = 'block';
        dropZone.classList.add('upload-done');
      }
    }

    /* ── Click to open file dialog ── */
    dropZone.addEventListener('click', function(e) {
      if (e.target === changeImageBtn) return; // handled separately
      if (dropZone.classList.contains('uploading')) return;
      fileInput.click();
    });

    /* ── "Đổi ảnh" button ── */
    changeImageBtn.addEventListener('click', function(e) {
      e.stopPropagation();
      imageUrlField.value = '';
      submitBtn.disabled = true;
      submitBtn.title = 'Vui lòng upload ảnh thành công trước';
      setZoneState('idle');
      hideError();
      fileInput.value = '';
      fileInput.click();
    });

    /* ── File input change ── */
    fileInput.addEventListener('change', function() {
      if (this.files && this.files[0]) {
        uploadFile(this.files[0]);
      }
    });

    /* ── Drag & Drop events ── */
    dropZone.addEventListener('dragover', function(e) {
      e.preventDefault();
      if (!dropZone.classList.contains('uploading') && !dropZone.classList.contains('upload-done')) {
        dropZone.classList.add('drag-over');
      }
    });
    dropZone.addEventListener('dragleave', function() {
      dropZone.classList.remove('drag-over');
    });
    dropZone.addEventListener('drop', function(e) {
      e.preventDefault();
      dropZone.classList.remove('drag-over');
      if (dropZone.classList.contains('uploading')) return;
      var file = e.dataTransfer.files[0];
      if (file && file.type.startsWith('image/')) {
        uploadFile(file);
      } else {
        showError('Vui lòng thả file ảnh (JPG, PNG, GIF, WebP).');
      }
    });

    /* ── Firebase upload ── */
    function uploadFile(file) {
      if (!storage) {
        showError('Firebase chưa sẵn sàng.');
        return;
      }
      hideError();
      imageUrlField.value = '';
      submitBtn.disabled = true;

      // Show local preview immediately while uploading
      var reader = new FileReader();
      reader.onload = function(ev) {
        imagePreview.src = ev.target.result;
      };
      reader.readAsDataURL(file);

      setZoneState('uploading');
      progressBar.style.width = '0%';
      progressBar.textContent = '0%';

      var fileName = Date.now() + '_' + file.name.replace(/\s+/g, '_');
      var storageRef = storage.ref('coffeeblend_products/' + fileName);
      var uploadTask = storageRef.put(file);

      uploadTask.on(
        'state_changed',
        function(snapshot) {
          var pct = Math.round((snapshot.bytesTransferred / snapshot.totalBytes) * 100);
          progressBar.style.width = pct + '%';
          progressBar.textContent = pct + '%';
        },
        function(error) {
          var msg = 'Upload thất bại: ' + error.message;
          if (error.code === 'storage/unauthorized') {
            msg = 'Firebase Storage từ chối ghi — kiểm tra Storage Rules (allow write: if true).';
          }
          showError(msg);
          setZoneState('idle');
        },
        function() {
          uploadTask.snapshot.ref.getDownloadURL().then(function(url) {
            imageUrlField.value = url;
            imagePreview.src = url;
            uploadedName.textContent = file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)';
            setZoneState('done');

            // ✅ Enable Create button now
            submitBtn.disabled = false;
            submitBtn.title = '';
          });
        }
      );
    }

    /* ── Form submit ── */
    document.getElementById('createProductForm').addEventListener('submit', function(e) {
      if (!imageUrlField.value) {
        e.preventDefault();
        showError('Ảnh chưa được upload. Vui lòng kéo thả hoặc chọn ảnh trước.');
        return;
      }
      submitBtn.disabled = true;
      submitStatus.style.display = 'inline';
    });

  }());
</script>

@endsection