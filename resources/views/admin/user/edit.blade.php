<form method="post" action="{{ route('admin.'. request()->segment(2) .'.update', $user->id) }}" id="main-form-modal">
    @csrf
    @method('PUT')
    <div class="form-group">
        <label>Nama</label>
        <input type="text" class="form-control" name="full_name" placeholder="Nama" value="{{ $user->full_name }}">
        <small class="text-danger full_name-invalid"></small>
    </div>
    <div class="form-group">
        <label>Email</label>
        <input type="email" class="form-control" name="email" placeholder="Email" value="{{ $user->email }}">
        <small class="text-danger email-invalid"></small>
    </div>
    <div class="form-group">
        <label>Username</label>
        <input type="text" class="form-control" name="username" placeholder="Username" value="{{ $user->username }}">
        <small class="text-danger username-invalid"></small>
    </div>
    <div class="form-group">
        <label>Password</label>
        <input type="password" class="form-control" name="password" placeholder="Kosongkan jika tidak dibutuhkan">
        <small class="text-danger password-invalid"></small>
    </div>
    <div class="form-group">
        <label>Nomor HP / Whatsapp</label>
        <input type="number" class="form-control" name="phone_number" placeholder="Nomor HP / Whatsapp" value="{{ $user->phone_number }}">
        <small class="text-danger phone_number-invalid"></small>
    </div>
    <div class="form-group">
        <label>Saldo</label>
        <input type="number" class="form-control" name="balance" placeholder="Saldo" value="{{ $user->balance }}">
        <small class="text-danger balance-invalid"></small>
    </div>
    <div class="form-group">
        <label>Level</label>
        <select name="level" id="level" class="form-control">
            <option value="">Pilih salah satu</option>
            @foreach ($level as $key => $value)
                <option value="{{ $key }}" {{ ($user->level == $key) ? 'selected' : '' }}> {{ $value }}</option>
            @endforeach
        </select>
        <small class="text-danger level-invalid"></small>
    </div>
    <div class="form-group">
        <label>Status </label>
        <div class="custom-control custom-switch">
            <input type="checkbox" name="is_active" value="1" class="custom-control-input" id="isActive" {{ $user->is_active == 1 ? 'checked' : '' }}>
            <label class="custom-control-label" for="isActive"></label>
        </div>
        <small class="text-danger is_active-invalid"></small>
    </div>
    <hr />
    <div class="text-right">
        <button type="reset" class="btn btn-danger"><i class="fa fa-redo"></i> Reset</button>
        <button type="submit" class="btn btn-success" name="button_form_modal"><i class="fa fa-check"></i>
            Submit</button>
    </div>
</form>
<script>

</script>
<script src="{{ asset('custom/main.custom.js') }}"></script>