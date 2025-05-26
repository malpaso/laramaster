<script setup lang="ts">
import AddressAutocomplete from '@/components/AddressAutocomplete.vue';
import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
} from '@/components/ui/alert-dialog';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import InputError from '@/components/InputError.vue';
import { Separator } from '@/components/ui/separator';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { Loader2, Plus, Trash2, Users } from 'lucide-vue-next';
import { ref, type Ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Companies', href: '/companies' },
    { title: 'Edit', href: '/companies' },
];

// Types
interface Employee {
    id: number;
    first_name: string;
    last_name: string;
    email: string;
    address: string;
    company_id: number;
    created_at: string;
    updated_at: string;
}

interface Company {
    id: number;
    name: string;
    abn: string;
    email: string;
    address: string;
    employees?: Employee[];
    created_at: string;
    updated_at: string;
}

interface CompanyFormData {
    name: string;
    abn: string;
    email: string;
    address: string;
}

interface EmployeeFormData {
    company_id: number;
    first_name: string;
    last_name: string;
    email: string;
    address: string;
}

// Props
interface Props {
    company: Company;
}

const props = defineProps<Props>();

// Reactive state
const showAddForm: Ref<boolean> = ref(false);
const showDeleteModal: Ref<boolean> = ref(false);
const employeeToDelete: Ref<Employee | null> = ref(null);

// Forms
const form = useForm<CompanyFormData>({
    name: props.company.name,
    abn: props.company.abn,
    email: props.company.email,
    address: props.company.address,
});

const employeeForm = useForm<EmployeeFormData>({
    company_id: props.company.id,
    first_name: '',
    last_name: '',
    email: '',
    address: '',
});

const handleCompanyAddressSelected = (address) => {
    form.address = `${address.street}, ${address.city}, ${address.state} ${address.postCode}`;
};

const handleCompanyAddressCleared = () => {
    form.address = '';
};

const handleEmployeeAddressSelected = (address) => {
    employeeForm.address = `${address.street}, ${address.city}, ${address.state} ${address.postCode}`;
};

const handleEmployeeAddressCleared = () => {
    employeeForm.address = '';
};

const deleteForm = useForm({});

// Methods
const updateCompany = (): void => {
    console.log('Updating company with data:', form);
    form.put(route('companies.update', props.company.id), {
        onSuccess: () => {
        },
        onError: () => {
            console.error('Failed to update company:', form.errors);
        },
    });
};

const createEmployee = (): void => {
    employeeForm.post(route('employees.store'), {
        onSuccess: () => {
            resetEmployeeForm();
            showAddForm.value = false;
        },
    });
};

const resetEmployeeForm = (): void => {
    employeeForm.reset();
    employeeForm.clearErrors();
};

const cancelAddEmployee = (): void => {
    resetEmployeeForm();
    showAddForm.value = false;
};

const confirmDeleteEmployee = (employee: Employee): void => {
    employeeToDelete.value = employee;
    showDeleteModal.value = true;
};

const deleteEmployee = (): void => {
    if (employeeToDelete.value) {
        deleteForm.delete(route('employees.destroy', employeeToDelete.value.id), {
            onSuccess: () => {
                cancelDelete();
            },
        });
    }
};

const cancelDelete = (): void => {
    showDeleteModal.value = false;
    employeeToDelete.value = null;
    deleteForm.clearErrors();
};
</script>

<template>
    <Head title="Edit Company" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="space-y-2">
                <h1 class="text-3xl font-bold tracking-tight">Edit Company</h1>
                <p class="text-muted-foreground">Manage company information and employees</p>
            </div>

            <!-- Company Form -->
            <Card>
                <CardHeader>
                    <CardTitle>Company Information</CardTitle>
                    <CardDescription>Update company details</CardDescription>
                </CardHeader>
                <CardContent>
                    <form @submit.prevent="updateCompany" class="space-y-4">
                        <div class="space-y-2">
                            <Label for="company-name">Company Name</Label>
                            <Input id="company-name" v-model="form.name" type="text" required />
                            <InputError :message="form.errors.name" />
                        </div>

                        <div class="space-y-2">
                            <Label for="company-abn">ABN</Label>
                            <Input id="company-abn" v-model="form.abn" type="text" required />
                            <InputError :message="form.errors.abn" />
                        </div>

                        <div class="space-y-2">
                            <Label for="company-email">Email</Label>
                            <Input id="company-email" v-model="form.email" type="email" required />
                            <InputError :message="form.errors.email" />
                        </div>

                        <div class="space-y-2">
                            <Label for="company-address">Address - {{ form.address }}</Label>
                            <AddressAutocomplete @address-selected="handleCompanyAddressSelected" @address-cleared="handleCompanyAddressCleared" />
                            <InputError :message="form.errors.address" />
                        </div>

                        <Button type="submit" :disabled="form.processing" class="w-full cursor-pointer sm:w-auto">
                            <Loader2 v-if="form.processing" class="mr-2 h-4 w-4 animate-spin" />
                            {{ form.processing ? 'Updating...' : 'Update Company' }}
                        </Button>
                    </form>
                </CardContent>
            </Card>

            <!-- Employee Management Section -->
            <Card>
                <CardHeader>
                    <div class="flex items-center justify-between">
                        <div>
                            <CardTitle>Employees</CardTitle>
                            <CardDescription>Manage company employees</CardDescription>
                        </div>
                        <Button @click="showAddForm = !showAddForm" variant="outline" class="cursor-pointer">
                            <Plus class="mr-2 h-4 w-4" />
                            {{ showAddForm ? 'Cancel' : 'Add Employee' }}
                        </Button>
                    </div>
                </CardHeader>

                <CardContent class="space-y-6">
                    <!-- Add Employee Form -->
                    <div v-if="showAddForm">
                        <Separator class="mb-6" />
                        <div class="space-y-4">
                            <h3 class="text-lg font-semibold">Add New Employee</h3>
                            <form @submit.prevent="createEmployee" class="space-y-4">
                                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                    <div class="space-y-2">
                                        <Label for="employee-name">First Name</Label>
                                        <Input id="employee-firstname" v-model="employeeForm.first_name" type="text" required />
                                    </div>

                                    <div class="space-y-2">
                                        <Label for="employee-name">Last Name</Label>
                                        <Input id="employee-lastname" v-model="employeeForm.last_name" type="text" required />
                                    </div>

                                    <div class="space-y-2">
                                        <Label for="employee-email">Email</Label>
                                        <Input id="employee-email" v-model="employeeForm.email" type="email" required />
                                    </div>

                                    <div class="space-y-2">
                                        <Label for="employee-address">Address</Label>
                                        <AddressAutocomplete
                                            @address-selected="handleEmployeeAddressSelected"
                                            @address-cleared="handleEmployeeAddressCleared"
                                        />
                                    </div>
                                </div>

                                <div class="flex flex-col gap-2 sm:flex-row">
                                    <Button type="submit" :disabled="employeeForm.processing" class="cursor-pointer">
                                        <Loader2 v-if="employeeForm.processing" class="mr-2 h-4 w-4 animate-spin" />
                                        {{ employeeForm.processing ? 'Creating...' : 'Create Employee' }}
                                    </Button>
                                    <Button type="button" variant="outline" @click="cancelAddEmployee"> Cancel </Button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Employees List -->
                    <div v-if="company.employees && company.employees.length > 0">
                        <Separator v-if="showAddForm" class="mb-6" />
                        <div class="rounded-md border">
                            <Table>
                                <TableHeader>
                                    <TableRow>
                                        <TableHead>Name</TableHead>
                                        <TableHead>Email</TableHead>
                                        <TableHead>Address</TableHead>
                                        <TableHead class="w-[100px]">Actions</TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    <TableRow v-for="employee in company.employees" :key="employee.id">
                                        <TableCell class="font-medium">{{ employee.last_name }}, {{ employee.first_name }}</TableCell>
                                        <TableCell>{{ employee.email }}</TableCell>
                                        <TableCell>{{ employee.address }}</TableCell>
                                        <TableCell>
                                            <Button
                                                @click="confirmDeleteEmployee(employee)"
                                                variant="ghost"
                                                size="sm"
                                                class="text-destructive hover:text-destructive"
                                            >
                                                <Trash2 class="h-4 w-4" />
                                            </Button>
                                        </TableCell>
                                    </TableRow>
                                </TableBody>
                            </Table>
                        </div>
                    </div>

                    <div v-else class="py-12 text-center">
                        <Users class="text-muted-foreground mx-auto mb-4 h-12 w-12" />
                        <h3 class="mb-2 text-lg font-semibold">No employees found</h3>
                        <p class="text-muted-foreground mb-4">This company doesn't have any employees yet.</p>
                        <Button @click="showAddForm = true" variant="outline" class="cursor-pointer">
                            <Plus class="mr-2 h-4 w-4" />
                            Add Employee
                        </Button>
                    </div>
                </CardContent>
            </Card>

            <!-- Delete Confirmation Dialog -->
            <AlertDialog :open="showDeleteModal" @update:open="showDeleteModal = $event">
                <AlertDialogContent>
                    <AlertDialogHeader>
                        <AlertDialogTitle>Are you sure?</AlertDialogTitle>
                        <AlertDialogDescription>
                            This will permanently delete <strong>{{ employeeToDelete?.first_name }} {{ employeeToDelete?.last_name }}</strong>
                            from the system. This action cannot be undone.
                        </AlertDialogDescription>
                    </AlertDialogHeader>
                    <AlertDialogFooter>
                        <AlertDialogCancel @click="cancelDelete">Cancel</AlertDialogCancel>
                        <AlertDialogAction
                            @click="deleteEmployee"
                            :disabled="deleteForm.processing"
                            class="bg-destructive text-destructive-foreground hover:bg-destructive/90"
                        >
                            <Loader2 v-if="deleteForm.processing" class="mr-2 h-4 w-4 animate-spin" />
                            {{ deleteForm.processing ? 'Deleting...' : 'Delete Employee' }}
                        </AlertDialogAction>
                    </AlertDialogFooter>
                </AlertDialogContent>
            </AlertDialog>
        </div>
    </AppLayout>
</template>
