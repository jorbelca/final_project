<script setup>
import { ref } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
import Banner from "@/Components/_Default/Banner.vue";
import Dropdown from "@/Components/_Default/Dropdown.vue";
import DropdownLink from "@/Components/_Default/DropdownLink.vue";
import NavLink from "@/Components/_Default/NavLink.vue";
import ResponsiveNavLink from "@/Components/_Default/ResponsiveNavLink.vue";
import Footer from "@/Components/_Default/Footer.vue";
import Logo from "@/Components/Logo/coreLogo.vue";
import DarkModeBtn from "@/Components/Buttons/DarkModeBtn.vue";

defineProps({
    title: String,
});

const showingNavigationDropdown = ref(false);

const switchToTeam = (team) => {
    router.put(
        route("current-team.update"),
        {
            team_id: team.id,
        },
        {
            preserveState: false,
        }
    );
};

const logout = () => {
    router.post(route("logout"));
};
</script>

<template>
    <div>
        <Head :title="title" />

        <Banner />

        <div
            class="w-full bg-background_contrast"
            style="min-height: calc(100vh - 45px)"
        >
            <nav class="bg-background_nav border-b border-border relative">
                <!-- Primary Navigation Menu -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Logo -->

                            <div class="shrink-0 flex items-center">
                                <Link :href="route('budgets.index')">
                                    <Logo />
                                </Link>
                            </div>

                            <!-- Navigation Links -->
                            <div
                                class="hidden space-x-6 md:-my-px md:ms-5 md:flex"
                            >
                                <NavLink
                                    :href="route('budgets.index')"
                                    :active="route().current('budgets.index')"
                                >
                                    Presupuestos
                                </NavLink>
                                <NavLink
                                    :href="route('clients.index')"
                                    :active="route().current('clients.index')"
                                >
                                    Clientes
                                </NavLink>
                                <NavLink
                                    :href="route('costs.index')"
                                    :active="route().current('costs.index')"
                                >
                                    Costes
                                </NavLink>
                                <NavLink
                                    v-if="
                                        +$page?.props?.auth?.user?.active === 1
                                    "
                                    :href="route('prompt.index')"
                                    :active="route().current('prompt.index')"
                                >
                                    IA
                                </NavLink>
                                <NavLink
                                    v-if="
                                        +$page?.props?.auth?.user?.active === 1
                                    "
                                    :href="route('statistics')"
                                    :active="route().current('statistics')"
                                >
                                    Stats
                                </NavLink>
                                <NavLink
                                    v-if="$page?.props?.auth?.user?.admin == 1"
                                    :href="route('admin')"
                                    :active="$page.url === '/admin'"
                                >
                                    Admin
                                </NavLink>

                                <NavLink
                                    :href="route('support.index')"
                                    :active="route().current('support.index')"
                                >
                                    Soporte
                                </NavLink>
                            </div>
                        </div>

                        <div class="hidden sm:flex sm:items-center sm:ms-6">
                            <div class="ms-3 relative">
                                <!-- Teams Dropdown -->
                                <Dropdown
                                    v-if="$page.props.jetstream.hasTeamFeatures"
                                    align="right"
                                    width="60"
                                >
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                type="button"
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150"
                                            >
                                                {{
                                                    $page.props.auth.user
                                                        ?.current_team.name
                                                }}

                                                <svg
                                                    class="ms-2 -me-0.5 size-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke-width="1.5"
                                                    stroke="currentColor"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9"
                                                    />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <div class="w-60">
                                            <!-- Team Management -->
                                            <div
                                                class="block px-4 py-2 text-xs text-gray-400"
                                            >
                                                Gestion de Equipos
                                            </div>

                                            <!-- Team Settings -->
                                            <DropdownLink
                                                :href="
                                                    route(
                                                        'teams.show',
                                                        $page.props.auth.user
                                                            .current_team
                                                    )
                                                "
                                            >
                                                Ajustes del Equipo
                                            </DropdownLink>

                                            <DropdownLink
                                                v-if="
                                                    $page.props.jetstream
                                                        .canCreateTeams
                                                "
                                                :href="route('teams.create')"
                                            >
                                                Crear un nuevo Equipo
                                            </DropdownLink>

                                            <!-- Team Switcher -->
                                            <template
                                                v-if="
                                                    $page.props.auth.user
                                                        .all_teams.length > 1
                                                "
                                            >
                                                <div
                                                    class="border-t border-gray-200"
                                                />

                                                <div
                                                    class="block px-4 py-2 text-xs text-gray-400"
                                                >
                                                    Cambiar de Equipo
                                                </div>

                                                <template
                                                    v-for="team in $page.props
                                                        .auth.user.all_teams"
                                                    :key="team.id"
                                                >
                                                    <form
                                                        @submit.prevent="
                                                            switchToTeam(team)
                                                        "
                                                    >
                                                        <DropdownLink
                                                            as="button"
                                                        >
                                                            <div
                                                                class="flex items-center"
                                                            >
                                                                <svg
                                                                    v-if="
                                                                        team.id ==
                                                                        $page
                                                                            .props
                                                                            .auth
                                                                            .user
                                                                            .current_team_id
                                                                    "
                                                                    class="me-2 size-5 text-green-400"
                                                                    xmlns="http://www.w3.org/2000/svg"
                                                                    fill="none"
                                                                    viewBox="0 0 24 24"
                                                                    stroke-width="1.5"
                                                                    stroke="currentColor"
                                                                >
                                                                    <path
                                                                        stroke-linecap="round"
                                                                        stroke-linejoin="round"
                                                                        d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                                                    />
                                                                </svg>

                                                                <div>
                                                                    {{
                                                                        team.name
                                                                    }}
                                                                </div>
                                                            </div>
                                                        </DropdownLink>
                                                    </form>
                                                </template>
                                            </template>
                                        </div>
                                    </template>
                                </Dropdown>
                            </div>

                            <!-- Settings Dropdown -->
                            <div class="hidden mr-8 relative md:flex">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <button
                                            v-if="
                                                $page.props.auth.user
                                                    ?.profile_photo_path
                                            "
                                            class="flex items-center gap-1 text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition"
                                        >
                                            <img
                                                v-if="
                                                    $page.props.auth.user
                                                        ?.profile_photo_path
                                                "
                                                class="size-8 rounded-full object-cover"
                                                :src="
                                                    $page.props.auth.user
                                                        ?.profile_photo_path
                                                "
                                                :alt="
                                                    $page.props.auth.user?.name
                                                "
                                            />

                                            <span class="text-text">{{
                                                $page.props.auth.user?.name ??
                                                ""
                                            }}</span>
                                            <svg
                                                class="ms-2 -me-0.5 size-4"
                                                xmlns="http://www.w3.org/2000/svg"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke-width="1.5"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M19.5 8.25l-7.5 7.5-7.5-7.5"
                                                />
                                            </svg>
                                            <span
                                                class="pl-2"
                                                v-if="
                                                    +$page.props.auth.user
                                                        ?.active === 1
                                                "
                                                >🟢
                                            </span>
                                            <span class="pl-2" v-else>🔴</span>
                                        </button>

                                        <span
                                            v-else
                                            class="inline-flex text-text items-center justify-center size-8 rounded-full font-bold uppercase"
                                        >
                                            <button
                                                type="button"
                                                class="inline-flex text-text items-center px-3 py-2 border border-transparent text-sm leading-4 font-bold rounded-md hover:border focus:outline-none focus:bg-gray-100 active:bg-gray-100 dark:bg-gray-800 dark:active:bg-gray-600 dark:focus:bg-gray-600 transition ease-in-out duration-150"
                                            >
                                                {{
                                                    $page.props.auth.user
                                                        ?.name ?? ""
                                                }}

                                                <svg
                                                    class="ms-2 -me-0.5 size-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke-width="1.5"
                                                    stroke="currentColor"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        d="M19.5 8.25l-7.5 7.5-7.5-7.5"
                                                    />
                                                </svg>
                                                <span
                                                    class="pr-2 pl-2"
                                                    v-if="
                                                        +$page.props.auth.user
                                                            ?.active === 1
                                                    "
                                                    >🟢
                                                </span>
                                                <span class="pr-2 pl-2" v-else
                                                    >🔴</span
                                                >
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <!-- Account Management -->
                                        <div class="block px-4 py-2 text-xs">
                                            Gestión de Cuenta
                                        </div>

                                        <DropdownLink
                                            :href="route('profile.show')"
                                        >
                                            Perfil
                                        </DropdownLink>

                                        <DropdownLink
                                            v-if="
                                                $page.props.jetstream
                                                    .hasApiFeatures
                                            "
                                            :href="route('api-tokens.index')"
                                        >
                                            API Tokens
                                        </DropdownLink>

                                        <div class="border-t border-gray-200" />

                                        <!-- Authentication -->
                                        <form @submit.prevent="logout">
                                            <DropdownLink as="button">
                                                Cerrar Sesión
                                            </DropdownLink>
                                        </form>
                                    </template>
                                </Dropdown>
                            </div>
                            <span class="hidden md:block z-40">
                                <DarkModeBtn />
                            </span>
                        </div>

                        <!-- Hamburger -->
                        <div class="flex items-center md:hidden">
                            <button
                                class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
                                @click="
                                    showingNavigationDropdown =
                                        !showingNavigationDropdown
                                "
                            >
                                <svg
                                    class="size-6"
                                    stroke="currentColor"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex':
                                                !showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex':
                                                showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg></button
                            ><span class="md:hidden mt-1.5 ml-3">
                                <DarkModeBtn />
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div
                    :class="{
                        block: showingNavigationDropdown,
                        hidden: !showingNavigationDropdown,
                    }"
                    class="md:hidden"
                >
                    <div class="pt-2 pb-3 space-y-1">
                        <ResponsiveNavLink
                            :href="route('budgets.index')"
                            :active="route().current('budgets.index')"
                        >
                            Presupuestos
                        </ResponsiveNavLink>
                        <ResponsiveNavLink
                            :href="route('clients.index')"
                            :active="route().current('clients.index')"
                        >
                            Clientes
                        </ResponsiveNavLink>
                        <ResponsiveNavLink
                            :href="route('costs.index')"
                            :active="route().current('costs.index')"
                        >
                            Costes </ResponsiveNavLink
                        ><ResponsiveNavLink
                            v-if="+$page?.props?.auth?.user?.active === 1"
                            :href="route('prompt.index')"
                            :active="route().current('prompt.index')"
                        >
                            IA
                        </ResponsiveNavLink>
                        <ResponsiveNavLink
                            v-if="+$page?.props?.auth?.user?.active === 1"
                            :href="route('statistics')"
                            :active="route().current('statistics')"
                        >
                            Estadisticas
                        </ResponsiveNavLink>
                        <ResponsiveNavLink
                            v-if="$page?.props?.auth?.user?.admin == 1"
                            :href="route('admin')"
                            :active="$page.url === '/admin'"
                        >
                            Admin
                        </ResponsiveNavLink>
                        <ResponsiveNavLink
                            :href="route('support.index')"
                            :active="route().current('support.index')"
                        >
                            Soporte
                        </ResponsiveNavLink>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div class="pt-4 pb-1 border-t border-gray-200">
                        <div class="flex items-center px-4">
                            <div
                                v-if="
                                    $page.props.jetstream.managesProfilePhotos
                                "
                                class="shrink-0 me-3"
                            >
                                <img
                                    v-if="
                                        $page.props.auth.user
                                            ?.profile_photo_path
                                    "
                                    class="size-10 rounded-full object-cover"
                                    :src="
                                        $page.props.auth.user.profile_photo_path
                                    "
                                    :alt="$page.props.auth.user?.name"
                                />
                            </div>

                            <div class="flex flex-row justify-between w-full">
                                <div>
                                    <div
                                        class="font-medium text-base text-text"
                                    >
                                        {{ $page.props.auth.user?.name ?? "" }}
                                    </div>
                                    <div
                                        class="font-medium text-sm text-gray-500"
                                    >
                                        {{ $page.props.auth.user?.email ?? "" }}
                                    </div>
                                </div>
                                <span
                                    v-if="+$page.props.auth.user?.active === 1"
                                    >🟢
                                </span>
                                <span v-else>🔴</span>
                            </div>
                        </div>

                        <div class="mt-3 space-y-1 text-text">
                            <ResponsiveNavLink
                                :href="route('profile.show')"
                                :active="route().current('profile.show')"
                            >
                                Perfil
                            </ResponsiveNavLink>

                            <ResponsiveNavLink
                                v-if="$page.props.jetstream.hasApiFeatures"
                                :href="route('api-tokens.index')"
                                :active="route().current('api-tokens.index')"
                            >
                                API Tokens
                            </ResponsiveNavLink>

                            <!-- Authentication -->
                            <form method="POST" @submit.prevent="logout">
                                <ResponsiveNavLink as="button">
                                    Cerrar Sesión
                                </ResponsiveNavLink>
                            </form>

                            <!-- Team Management -->
                            <template
                                v-if="$page.props.jetstream.hasTeamFeatures"
                            >
                                <div class="border-t border-gray-200" />

                                <div
                                    class="block px-4 py-2 text-xs text-gray-400"
                                >
                                    Gestion de Equipos
                                </div>

                                <!-- Team Settings -->
                                <ResponsiveNavLink
                                    :href="
                                        route(
                                            'teams.show',
                                            $page.props.auth.user.current_team
                                        )
                                    "
                                    :active="route().current('teams.show')"
                                >
                                    Ajustes del Equipo
                                </ResponsiveNavLink>

                                <ResponsiveNavLink
                                    v-if="$page.props.jetstream.canCreateTeams"
                                    :href="route('teams.create')"
                                    :active="route().current('teams.create')"
                                >
                                    Create New Team
                                </ResponsiveNavLink>

                                <!-- Team Switcher -->
                                <template
                                    v-if="
                                        $page.props.auth.user.all_teams.length >
                                        1
                                    "
                                >
                                    <div class="border-t border-gray-200" />

                                    <div
                                        class="block px-4 py-2 text-xs text-gray-400"
                                    >
                                        Cambiar de Equipo
                                    </div>

                                    <template
                                        v-for="team in $page.props.auth.user
                                            .all_teams"
                                        :key="team.id"
                                    >
                                        <form
                                            @submit.prevent="switchToTeam(team)"
                                        >
                                            <ResponsiveNavLink as="button">
                                                <div class="flex items-center">
                                                    <svg
                                                        v-if="
                                                            team.id ==
                                                            $page.props.auth
                                                                .user
                                                                .current_team_id
                                                        "
                                                        class="me-2 size-5 text-green-400"
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        stroke-width="1.5"
                                                        stroke="currentColor"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                                        />
                                                    </svg>
                                                    <div>{{ team.name }}</div>
                                                </div>
                                            </ResponsiveNavLink>
                                        </form>
                                    </template>
                                </template>
                            </template>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header
                v-if="$slots.header"
                class="bg-background_transparent text-text"
            >
                <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>

        <Footer />
    </div>
</template>
