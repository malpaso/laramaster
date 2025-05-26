<script setup lang="ts">
import { buttonVariants } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { type Company, BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { Pencil, Plus } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Companies', href: '/companies' },
];

interface Props {
    companies: Company[];
}

defineProps<Props>();
</script>

<template>
    <Head title="Company List" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <Card>
                <CardHeader>
                    <div class="flex items-center justify-between">
                        <div>
                            <CardTitle>Companies</CardTitle>
                            <CardDescription>List all companies</CardDescription>
                        </div>
                        <Link :class="buttonVariants({ variant: 'outline' })" :href="route('companies.create')">
                            <Plus class="mr-2 h-4 w-4" />
                            Add Company
                        </Link>
                    </div>
                </CardHeader>
                <CardContent class="space-y-6">
                    <div class="rounded-md border">
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>Company</TableHead>
                                    <TableHead>ABN</TableHead>
                                    <TableHead>Email</TableHead>
                                    <TableHead>Employees</TableHead>
                                    <TableHead class="w-[100px]">Actions</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-for="company in companies" :key="company.id">
                                    <TableCell>{{ company.name }}</TableCell>
                                    <TableCell>{{ company.abn }}</TableCell>
                                    <TableCell>{{ company.email }}</TableCell>
                                    <TableCell>{{ company.employees_count }}</TableCell>
                                    <TableCell>
                                        <Link :class="buttonVariants({ variant: 'ghost' })" :href="`/companies/${company.id}/edit`">
                                            <Pencil class="h-4 w-4" />
                                        </Link>
                                    </TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
