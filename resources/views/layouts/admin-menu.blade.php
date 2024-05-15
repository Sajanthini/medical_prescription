<div class = "background-pharmacy"></div>
<li class="nav-item active">
    <a class="nav-link" href="/home">Home
        @if(Auth::user()->unreadNotifications->count() > 0)
            <span class="badge bg-danger">{{ Auth::user()->unreadNotifications->count() }}</span>
        @endif
        <span class="sr-only"></span>
    </a>
</li>
<li class="nav-item active">
    <a class="nav-link" href="/prescriptions">Prescriptions</a>
</li>
<li class="nav-item">
    <a class="nav-link" href="/quotations">Quotation</a>
</li>
