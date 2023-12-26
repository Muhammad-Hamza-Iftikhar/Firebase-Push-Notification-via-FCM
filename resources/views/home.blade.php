@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Send Notification') }}</div>

                <div class="card-body">
                    <div class="alert alert-success" id="alert" role="alert" style="display: none">
                        Notification Sent Successfully
                    </div>
                    <div class="alert alert-danger" id="error" role="alert" style="display: none">
                        Fillout the Complete Form.
                        </br>
                        All Fields with <span class="text-danger">*</span> are required!
                    </div>
                    <form id="notificationForm">
                        @csrf
                        <div class="form-group">
                            <label>To<span class="text-danger">*</span></label>
                            <select class="form-control" style="width: 100%;" name="user_id" required>
                                <option value="" disabled selected>Select User</option>
                                @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Title<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="title" required>
                        </div>
                        @error('title')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                        <div class="form-group">
                            <label>Body<span class="text-danger">*</span></label>
                            <textarea class="form-control" name="body" required></textarea>
                            @error('body')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Additional Info</label>
                            <textarea class="form-control" name="data" required></textarea>
                            @error('data')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <button type="submit" id="sendNotification" class="btn btn-primary">Send Notification</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    function requestNotificationPermission() {
        const messaging = firebase.messaging();

        // Request permission for notifications
        messaging.requestPermission()
            .then(() => {
                // Permission granted
                getTokenAndSave();
            })
            .catch((err) => {
                console.error('Unable to get permission for notifications.', err);
            });
    }

    function getTokenAndSave() {
        const messaging = firebase.messaging();

        // Retrieve the device token
        messaging.getToken({
                vapidKey: 'BPIg5lTl4gnE5BCBfqbNrRXUUXZKHGHPT_PiZTnU4vM4Ad-XFS_7usqhzLIhvmha3gdAdM_N2BHpewew91jCsZU' // Replace with your VAPID key
            })
            .then((currentToken) => {
                if (currentToken) {
                    console.log('Device Token:', currentToken);
                    saveTokenToServer(currentToken); // Save the token to the database
                } else {
                    console.log('No registration token available.');
                }
            })
            .catch((err) => {
                console.error('An error occurred while retrieving token. ', err);
            });
    }

    function saveTokenToServer(token) {
        axios.post('{{route('save.token')}}', {
                    token: token
                }, {
                    headers: {
                        'X-CSRF-TOKEN': "{{csrf_token()}}"
                    , }
                , })
            .then(response => {
                console.log('Token saved successfully:', response.data);
            })
            .catch(error => {
                console.error('Error saving token:', error);
            });
    }
    requestNotificationPermission();
    $(document).ready(function() {
        $("#sendNotification").click(function() {
            event.preventDefault();
            var title = $("#title").val();
            var body = $("#body").val();

            var formData = $("#notificationForm").serialize();
            $.ajax({
                url: "{{ route('send.notification') }}"
                , type: "POST"
                , data: formData
                , success: function(response) {
                    $("#alert").show();
                    $("#error").hide();
                    var form = document.getElementById('notificationForm');
                    form.reset();
                    console.log("Notification sent successfully");
                }
                , error: function(xhr, status, error) {
                    $("#error").show();
                    $("#alert").hide();
                    console.error("Failed to send notification:", error);
                }
            });
        });
    });

</script>
@endsection
