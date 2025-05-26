<script setup lang="ts">
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Building2, Users } from 'lucide-vue-next'

interface Props {
  totalCompanies: number
  totalEmployees: number
}

defineProps<Props>()
</script>

<template>
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Total Companies Card -->
    <Card>
      <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
        <CardTitle class="text-sm font-bold text-muted-foreground">
          Companies
        </CardTitle>
        <Building2 class="h-4 w-4 text-muted-foreground" />
      </CardHeader>
      <CardContent>
        <div class="text-2xl font-bold">{{ totalCompanies.toLocaleString() }}</div>
        <p class="text-xs text-muted-foreground mt-1">
          Active companies in system
        </p>
      </CardContent>
    </Card>

    <!-- Total Employees Card -->
    <Card>
      <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
        <CardTitle class="text-sm font-bold text-muted-foreground">
          Employees
        </CardTitle>
        <Users class="h-4 w-4 text-muted-foreground" />
      </CardHeader>
      <CardContent>
        <div class="text-2xl font-bold">{{ totalEmployees.toLocaleString() }}</div>
        <p class="text-xs text-muted-foreground mt-1">
          Registered employees
        </p>
      </CardContent>
    </Card>
  </div>
</template>
