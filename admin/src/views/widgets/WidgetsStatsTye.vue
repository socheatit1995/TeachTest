<script setup>
import { onMounted, ref } from 'vue'
import { CChart } from '@coreui/vue-chartjs'
import { getStyle } from '@coreui/utils'

const props = defineProps({
    data: Object
})
const widgetChartRef1 = ref()
const widgetChartRef2 = ref()



onMounted(() => {
  document.documentElement.addEventListener('ColorSchemeChange', () => {
    if (widgetChartRef1.value) {
      widgetChartRef1.value.chart.data.datasets[0].pointBackgroundColor = getStyle('--cui-primary')
      widgetChartRef1.value.chart.update()
    }

    if (widgetChartRef2.value) {
      widgetChartRef2.value.chart.data.datasets[0].pointBackgroundColor = getStyle('--cui-info')
      widgetChartRef2.value.chart.update()
    }
  })
})
</script>

<template>
  <CRow :xs="{ gutter: 3 }">
    <CCol :sm="6" :xl="4" :xxl="4">
        <CCard
        class="mb-3  h-180"
        color="primary"
        text-color="white"
        >
        <CCardBody>
            <CCardTitle class="fs-4 fw-semibold text-center"> {{  props?.data?.totalUser }}</CCardTitle>
            <CCardText class="fs-4 text-center">
                Total User
            </CCardText>
        </CCardBody>
        </CCard>
    </CCol>
    <CCol :sm="6" :xl="4" :xxl="4">
        <CCard
        class="mb-3  h-180"
        color="info"
        text-color="white"
        >
        <CCardBody>
            <CCardTitle class="fs-4 fw-semibold text-center"> {{  props?.data?.totalCustomer }}</CCardTitle>
            <CCardText class="fs-4 text-center">
               Total Customer 
            </CCardText>
        </CCardBody>
        </CCard>
    </CCol>
    <CCol :sm="6" :xl="4" :xxl="4">
        <CCard
        class="mb-3  h-180"
        color="warning"
        text-color="white"
        >
        <CCardBody>
            <CCardTitle class="fs-4 fw-semibold text-center"> {{  props?.data?.totalPuchase }}</CCardTitle>
            <CCardText class="fs-4 text-center">
                Customer Purchase
            </CCardText>
        </CCardBody>
        </CCard>
    </CCol>
   
    
  </CRow>
</template>
<style scoped>
.h-180 {
    padding-top: 30px;
    padding-bottom: 30px;
    min-height: 100px;
}
</style>
