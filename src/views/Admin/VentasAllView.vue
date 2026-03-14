<template>
  <AdminLayout>
    <PageBreadcrumb :pageTitle="currentPageTitle" />

    <div class="col-span-12 space-y-6 xl:col-span-7">

      <VentasAll />

    </div>
    
  </AdminLayout>
</template>

<script setup>
import { ref } from "vue";
import PageBreadcrumb from "@/components/common/PageBreadcrumb.vue";
import AdminLayout from "@/components/layout/AdminLayout.vue";
//import ComponentCard from "@/components/common/ComponentCard.vue";
import MonthlySale from '@/components/ecommerce/MonthlySale.vue'
import VentasAll from "@/components/admin/VentasAll.vue";
const currentPageTitle = ref("Ventas");
</script>
