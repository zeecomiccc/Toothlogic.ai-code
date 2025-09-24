<div class="d-flex gap-3 align-items-center">
  <img src="{{ optional($data->user)->profile_image ?? default_user_avatar() }}" alt="avatar" class="avatar avatar-40 rounded-pill">
  <div class="text-start">
    <h6 class="m-0">
      <a href="{{ route('backend.customers.patient_detail', ['id' => $data->user_id]) }}" class="text-decoration-none text-primary">
        {{ optional($data->user)->full_name ?? default_user_name() }}
      </a>
    </h6>
    <span>{{ optional($data->user)->email ?? '--' }}</span>
  </div>
</div>
