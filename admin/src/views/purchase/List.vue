<template>
    <CRow>
      <CCol :xs="12">
            <CCard class="mb-4">
            <CCardHeader>
                <CRow :xs="{ gutterX: 5 }">
                    <CCol>
                    <div class="p-3 "> 
                        <strong>Customer</strong> 
                    </div>
                    </CCol>
                    <CCol>
                    <div class="p-3 text-rigth" >
                        <CButton
                        @click="goToCreate()"
                        color="primary" >Create New Purchase </CButton>
                    </div>
                    </CCol> 
                </CRow>
            </CCardHeader>
            <CCardBody>
               
                <CTable>
                    <CTableHead>
                        <CTableRow>
                            <CTableHeaderCell scope="col">#</CTableHeaderCell>
                            <CTableHeaderCell scope="col">Customer</CTableHeaderCell>
                            <CTableHeaderCell scope="col">Amount</CTableHeaderCell>
                        </CTableRow>
                    </CTableHead>

                    <div v-if="loading">Loading...</div>
                    <div v-else-if="error">Error: {{ error.message }}</div>
                   <CTableBody v-else-if="PurchaseData">
                        <CTableRow v-for=" (item, index) in PurchaseData" :key="item.id">
                            <CTableHeaderCell scope="row">{{ index +1  }}</CTableHeaderCell>
                            <CTableDataCell>{{ item?.customer?.name }}</CTableDataCell>
                            <CTableDataCell>{{ item.amount }}</CTableDataCell>
                        </CTableRow>
                    </CTableBody>     
                </CTable>
            </CCardBody>
            </CCard>
            
        </CCol>
    </CRow>
</template>
<script >
import { ref , watch, onMounted, computed} from 'vue'
import { useQuery, useMutation } from '@vue/apollo-composable'
import { gql } from 'graphql-tag'
import router from '@/router'
export default {
    setup() {
        const PurchaseData = ref([]);
        const users = ref('')
        const visibleModale = ref(false)

        const QUERY_PURCHASE = gql`
           query {
                purchaseAll {
                    customer {
                    id,name, phone_number, score
                    }
                    amount
                }
            }
        `;


        const { result, loading, error, refetch }  = useQuery(QUERY_PURCHASE);
        PurchaseData.value = result?.value?.purchaseAll;  

        watch(result, value => {
            PurchaseData.value = value?.purchaseAll
            console.log(PurchaseData.value)
        })
        
        refetch();
        const goToCreate = () => router.replace({name: 'create-purchase'})

        const showModel = () => {
            visibleModale.value = true
            alert(visibleModale.value )
        }

        const closeModel = () => {
            visibleModale.value = false
        }

        return {
            showModel,
            closeModel,
            PurchaseData,
            goToCreate,
            loading,
            error,
        }
    },

   
}
</script> 
<style scoped>
/* .text-rigth {
    text-align: end !important;
} */
 
</style>