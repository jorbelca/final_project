<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link } from "@inertiajs/vue3";
</script>

<template>
    <AppLayout title="Budgets">
        <template #header>
            <div class="flex align-center justify-center gap-5 items-end">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    We coudln't find what you wanted
                </h2>
                <Link href="/budgets" class="btn btn-primary"> Home </Link>
            </div>
        </template>
    </AppLayout>
</template>
