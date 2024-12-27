<!-- Lai paātrinātu darbu, galvenes sākuma makets paņemts no https://flowbite.com/docs/components/navbar/ -->
<nav class="bg-white shadow-3xl dark:bg-gray-900">
    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
        <a href="/BalticLogi/public/" class="flex items-center space-x-3 rtl:space-x-reverse">
            <img src="https://i.ibb.co/vj61MnB/baltic-logi-high-resolution-logo-transparent.png" class="h-8" alt="BalticLogi" />
        </a>
        <button data-collapse-toggle="navbar-default" type="button"
                class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
                aria-controls="navbar-default" aria-expanded="false" onclick="toggleNavbar()">
            <span class="sr-only">Open main menu</span>
            <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M1 1h15M1 7h15M1 13h15" />
            </svg>
        </button>
        <div class="hidden w-full md:block md:w-auto" id="navbar-default">
            <ul
                class="font-medium flex flex-col p-4 md:p-0 mt-4 border border-gray-100 rounded-lg bg-gray-50 md:flex-row md:space-x-8 rtl:space-x-reverse md:mt-0 md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">
                <li>
                    <a href="/BalticLogi/public"
                       class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-primary md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">
                        Galvenā
                    </a>
                </li>
                <li>
                    <a href="/BalticLogi/public/products.php"
                       class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-primary md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Logi</a>
                </li>
                <li>
                    <a href="/BalticLogi/public/contacts.php"
                       class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-primary md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Kontakti</a>
                </li>
                <!-- Pārbauda vai lietotājs ir pierakstījies vai nē -->
                <li class="relative group">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <button onclick="showLogoutTooltip()"
                                class="block py-2 px-3 text-gray-900 rounded 
                                    hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-primary md:p-0 
                                    dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 
                                    dark:hover:text-white md:dark:hover:bg-transparent"
                        >
                            Izrakstīties
                        </button>
                        <div id="logoutTooltip"
                             class="absolute left-1/2 transform -translate-x-1/2 border mt-2 w-64 shadow-3xl bg-white text-sm rounded py-2 px-4 opacity-0 pointer-events-none transition-opacity duration-300">
                            Vai tiešām vēlaties izrakstīties?
                            <div class="flex justify-end mt-2">
                                <button onclick="hideLogoutTooltip()" class="mr-2 text-gray-600 hover:text-gray-300">Atcelt</button>
                                <a href="/BalticLogi/src/logout.php" class="text-danger hover:underline">Izrakstīties</a>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="/BalticLogi/public/login.php"
                           class="block py-2 px-3 text-gray-900 rounded 
                                hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-primary md:p-0 
                                dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white 
                                  md:dark:hover:bg-transparent"
                            >
                            Pierakstīties
                        </a>
                    <?php endif; ?>
                </li>
                <!-- Parādās ja lietotājs ir admins -->
                <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                    <li>
                    <a href="/BalticLogi/public/adminDashboard.php"
                            class="block py-2 px-3 text-gray-900 rounded 
                                 hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-primary md:p-0 
                                 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 
                                 dark:hover:text-white md:dark:hover:bg-transparent"
                            >
                            Admin
                        </a>
                    </li>
                <?php endif; ?>
                <li>
                    <a href="/BalticLogi/public/cart.php"
                       class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-primary md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent"
                    >
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M5.00014 14H18.1359C19.1487 14 19.6551 14 20.0582 13.8112C20.4134 13.6448 
                                    20.7118 13.3777 20.9163 13.0432C21.1485 12.6633 21.2044 12.16 21.3163 11.1534L21.9013 
                                    5.88835C21.9355 5.58088 21.9525 5.42715 21.9031 5.30816C21.8597 5.20366 21.7821 5.11697 
                                    21.683 5.06228C21.5702 5 21.4155 5 21.1062 5H4.50014M2 2H3.24844C3.51306 2 3.64537 2 3.74889 
                                    2.05032C3.84002 2.09463 3.91554 2.16557 3.96544 2.25376C4.02212 2.35394 4.03037 2.48599 
                                    4.04688 2.7501L4.95312 17.2499C4.96963 17.514 4.97788 17.6461 5.03456 17.7462C5.08446 17.8344 
                                    5.15998 17.9054 5.25111 17.9497C5.35463 18 5.48694 18 5.75156 18H19M7.5 21.5H7.51M16.5 
                                    21.5H16.51M8 21.5C8 21.7761 7.77614 22 7.5 22C7.22386 22 7 21.7761 7 21.5C7 21.2239 7.22386 
                                    21 7.5 21C7.77614 21 8 21.2239 8 21.5ZM17 21.5C17 21.7761 16.7761 22 16.5 22C16.2239 22 16 21.7761 
                                    16 21.5C16 21.2239 16.2239 21 16.5 21C16.7761 21 17 21.2239 17 21.5Z" 
                                stroke="#E07B39" 
                                stroke-width="2" 
                                stroke-linecap="round" 
                                stroke-linejoin="round"
                            />
                        </svg>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<script>
    function toggleNavbar() {
        const navbar = document.getElementById('navbar-default');
        navbar.classList.toggle('hidden');
    }

    function showLogoutTooltip() {
        const tooltip = document.getElementById('logoutTooltip');
        tooltip.classList.remove('opacity-0', 'pointer-events-none');
    }

    function hideLogoutTooltip() {
        const tooltip = document.getElementById('logoutTooltip');
        tooltip.classList.add('opacity-0', 'pointer-events-none');
    }
</script>
