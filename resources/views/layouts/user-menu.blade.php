<div class="background-user"></div>
<li class="nav-item active">
    <a class="nav-link" href="/">Home
        @if(Auth::user()->unreadNotifications->count() > 0)
            <span class="badge bg-danger">{{ Auth::user()->unreadNotifications->count() }}</span>
        @endif
        <span class="sr-only"></span>
    </a>
</li>
<li class="nav-item active">
    <a class="nav-link" href="/prescriptions">My Prescriptions</a>
</li>
<li class="nav-item active">
    <a class="nav-link" href="/create/prescription">Add Prescription</a>
</li>
<li class="nav-item">
    <a class="nav-link" href="/quotations">Quotations</a>
</li>