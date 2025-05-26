<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, type Ref } from 'vue'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AddressAutocomplete from '@/components/AddressAutocomplete.vue';
import { type BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Companies', href: '/companies' },
    { title: 'Create', href: '/companies' },
];

// Inertia form setup
const form = useForm({
  name: '',
  abn: '',
  email: '',
  address: '',
})

const handleAddressSelected = (address) => {
  form.address = `${address.street}, ${address.city}, ${address.state} ${address.postCode}`;
}

const handleAddressCleared = () => {
  form.address = null
}

// Submit form
const submitForm = (): void => {
    form.post(route('companies.store'), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Create Company" />
    <AppLayout :breadcrumbs="breadcrumbs">

        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="space-y-2">
                <h1 class="text-3xl font-bold tracking-tight">Create Company</h1>
                <p class="text-muted-foreground">Add new company information</p>
            </div>
            <!-- Company Form -->
            <Card>
                <CardHeader>
                    <CardTitle>Company Information</CardTitle>
                    <CardDescription>Add company details</CardDescription>
                </CardHeader>
                <CardContent>

                    <form class="space-y-6" @submit.prevent="submitForm">
                        <div class="grid gap-2">
                            <Label htmlFor="name">Company Name *</Label>
                            <Input id="name" v-model="form.name" class="mt-1 block w-1/2" />
                            <InputError :message="form.errors.name" />
                        </div>
                        <div class="grid gap-2">
                            <Label htmlFor="abn">ABN *</Label>
                            <Input id="abn" v-model="form.abn" class="mt-1 block w-1/2" />
                            <InputError :message="form.errors.abn" />
                        </div>
                        <div class="grid gap-2">
                            <Label htmlFor="email">Email *</Label>
                            <Input id="email" v-model="form.email" class="mt-1 block w-1/2" />
                            <InputError :message="form.errors.email" />
                        </div>
                        <div class="grid gap-2">
                            <Label htmlFor="address">Address *</Label>
                            <AddressAutocomplete
                                @address-selected="handleAddressSelected"
                                @address-cleared="handleAddressCleared"
                            />
                            <InputError :message="form.errors.address" />
                        </div>

                        <div class="flex items-center gap-4">
                            <Button :disabled="form.processing" variant="default" class="cursor-pointer">Create Company</Button>
                        </div>
                    </form>

                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
