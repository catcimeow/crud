<!-- In your profile.blade.php view file -->
<h1>Profile Page</h1>
<form id="logoutForm" onsubmit="event.preventDefault(); logout();">
    <button type="submit" class="btn btn-link">Logout</button>
</form>

<script>
    function logout() {
        fetch('{{ route('logout') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + localStorage.getItem('authToken') // Replace with your token storage mechanism
            },
            body: JSON.stringify({
                _token: '{{ csrf_token() }}'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.message === 'Logged out successfully.') {
                // Clear the token from local storage
                localStorage.removeItem('authToken');
                // Redirect to login page
                window.location.href = '{{ route('auth.login') }}';
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while logging out.');
        });
    }
</script>
