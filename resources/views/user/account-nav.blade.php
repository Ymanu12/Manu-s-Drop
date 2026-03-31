<ul class="account-nav rounded-xl border border-slate-200/70 bg-slate-50/80 p-4 shadow-sm transition-colors dark:border-slate-800 dark:bg-slate-950/70">
    <li><a href="{{route('user.index')}}" class="menu-link menu-link_us-s text-slate-700 transition-colors hover:text-slate-950 dark:text-slate-300 dark:hover:text-slate-100">Dashboard</a></li>
    <li><a href="{{route('user.orders')}}" class="menu-link menu-link_us-s text-slate-700 transition-colors hover:text-slate-950 dark:text-slate-300 dark:hover:text-slate-100">Orders</a></li>
    <li><a href="{{route('user.addresses')}}" class="menu-link menu-link_us-s text-slate-700 transition-colors hover:text-slate-950 dark:text-slate-300 dark:hover:text-slate-100">Addresses</a></li>
    <li><a href="{{route('user.account.details')}}" class="menu-link menu-link_us-s text-slate-700 transition-colors hover:text-slate-950 dark:text-slate-300 dark:hover:text-slate-100">Account Details</a></li>
    <!-- <li><a href="account-wishlist.html" class="menu-link menu-link_us-s text-slate-700 transition-colors hover:text-slate-950 dark:text-slate-300 dark:hover:text-slate-100">Wishlist</a></li> -->
    <li>
        <form method="POST" action="{{ route('logout') }}" id="logout-form">
            @csrf
            <a href="#" class="menu-link menu-link_us-s text-slate-700 transition-colors hover:text-slate-950 dark:text-slate-300 dark:hover:text-slate-100" 
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                Logout
            </a>
        </form>
    </li>
</ul>