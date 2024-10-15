<div class="col-md-6">
    <!-- general form elements -->
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Tambah Data</h3>
        </div>
        <!-- /.card-header -->
        <!-- form start -->
        <form method="POST" action="" enctype="multipart/form-data" id="myForm">
            <div class="card-body">
                <div class="form-group">
                    <label for="username">NIS <span class="text-danger">*</span></label>
                    <input type="number" class="form-control" name="nis" id="nis" placeholder="NIS" required>
                </div>
                <div class="form-group">
                    <label for="nama_siswa">Nama Siswa <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="nama_siswa" id="nama_siswa" placeholder="Nama Siswa" required>
                </div>
                <div class="form-group">
                    <label for="alamat">Alamat <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="alamat" id="alamat" placeholder="Alamat" required>
                </div>
                <div class="form-group">
                    <label for="tanggal_lahir">Tanggal Lahir <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" name="tanggal_lahir" id="tanggal_lahir" placeholder="Tanggal Lahir" required>
                </div>
            </div>