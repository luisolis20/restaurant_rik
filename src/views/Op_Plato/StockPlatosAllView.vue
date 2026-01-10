<template>
  <AdminLayout>
    <PageBreadcrumb :pageTitle="currentPageTitle" />
    <div class="space-y-5 sm:space-y-6">

      <StockPlatosAll />

    </div>
  </AdminLayout>
</template>

<script setup>
import { ref } from "vue";
import PageBreadcrumb from "@/components/common/PageBreadcrumb.vue";
import AdminLayout from "@/components/layout/AdminLayout.vue";
//import ComponentCard from "@/components/common/ComponentCard.vue";
import StockPlatosAll from "@/components/stok-platos/StockPlatosAll.vue";
const currentPageTitle = ref("Stock de Platos");
</script>
