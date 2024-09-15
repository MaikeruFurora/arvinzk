<div class="container d-flex justify-content-center align-items-center" style="margin-top: 100px">
    <div class="text-center p-4 rounded">
        <h4 class="mb-4" style="font-family: 'Poppins', sans-serif; font-weight: 700; color: #343a40;">{{ $checkUtilityNeeded['title'] }} </h4>
        <p class="mb-4" style="font-family: 'Poppins', sans-serif; color: #6c757d; font-size: 1.1rem;">{{ $checkUtilityNeeded['message'] }}</p>
        <a href="{{ route('app.user.config') }}" class="btn btn-dark btn-sm" style="font-family: 'Poppins', sans-serif;">Proceed</a>
    </div>
</div>