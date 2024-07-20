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
                        <CButton @click="goToCreate()"  color="primary" >New Customer</CButton>
                    </div>
                    </CCol>
                </CRow>
            </CCardHeader>
            <CCardBody>
               
                <CTable>
                    <CTableHead>
                        <CTableRow>
                        <CTableHeaderCell scope="col">#</CTableHeaderCell>
                        <CTableHeaderCell scope="col">Name</CTableHeaderCell>
                        <CTableHeaderCell scope="col">PhoneNumber</CTableHeaderCell>
                        <CTableHeaderCell scope="col">Reqister Date</CTableHeaderCell>
                        <CTableHeaderCell scope="col">Address</CTableHeaderCell>
                        <CTableHeaderCell scope="col">Score</CTableHeaderCell>
                        <CTableHeaderCell scope="col">Action</CTableHeaderCell>
                        </CTableRow>
                    </CTableHead>

                    <div v-if="loading">Loading...</div>
                    <div v-else-if="error">Error: {{ error.message }}</div>
                   <CTableBody v-else-if="CustomerData">
                        <CTableRow v-for=" (item, index) in CustomerData" :key="item.id">
                            <CTableHeaderCell scope="row">{{ index +1  }}</CTableHeaderCell>
                            <CTableDataCell>{{ item.name }}</CTableDataCell>
                            <CTableDataCell>{{ item.phone_number }}</CTableDataCell>
                            <CTableDataCell>{{ item.registration_date }}</CTableDataCell>
                            <CTableDataCell>{{ item.address }}</CTableDataCell>
                            <CTableDataCell>{{ item.score }}</CTableDataCell>
                            <CTableDataCell>
                                <CButton @click="postdeleteCustomer(item.id)"  color="danger" shape="rounded-pill">Delete</CButton>
                            </CTableDataCell>
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
        const CustomerData = ref([]);
        const messageSuccess = ref('')
        const users = ref('')

        const QUERY_CUSTOMER = gql`
            query {
                customers {
                    id, name, phone_number, registration_date ,address, score, created_at, updated_at
                }
            }
        `;

        const DELETE_CUSTOMER = gql`
            mutation deleteCustomer($id: ID!) {
                deleteCustomer(id: $id)
            }
        `;
        const { result, loading, error, refetch }  = useQuery(QUERY_CUSTOMER);
        CustomerData.value = result?.value?.customers;  

        watch(result, value => {
            CustomerData.value = value?.customers
            console.log(CustomerData.value)
        })
    
        refetch()
        const goToCreate = () => router.replace({name: 'create-customer'})

        const { mutate: deleteCustomer, onError } = useMutation(DELETE_CUSTOMER)
        const postdeleteCustomer = async (id) => {
            try {
                await deleteCustomer({id: id})
                CustomerData.value = CustomerData.value.filter((item) => item.id !== id);
                refetch();
                messageSuccess.value  = 'Message deleted successfully!';
            } catch (error) {
                console.error(error)
                alert('Error creating user')
            }
        }
       
        return {
            CustomerData,
            messageSuccess,
            goToCreate,
            postdeleteCustomer,
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