<template>
    <CRow>
      <CCol :xs="12">
            <CCard class="mb-4">
            <CCardHeader>
                <CRow :xs="{ gutterX: 5 }">
                    <CCol>
                    <div class="p-3 "> 
                        <strong>Users</strong> 
                    </div>
                    </CCol>
                    <CCol>
                    <div class="p-3 text-rigth" >
                        <CButton @click="goToCreate()"  color="primary" >New User</CButton>
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
                        <CTableHeaderCell scope="col">Email</CTableHeaderCell>
                        <CTableHeaderCell scope="col">UserName</CTableHeaderCell>
                        <CTableHeaderCell scope="col">Role</CTableHeaderCell>
                        <CTableHeaderCell scope="col">Action</CTableHeaderCell>
                        </CTableRow>
                    </CTableHead>

                    <div v-if="loading">Loading...</div>
                    <div v-else-if="error">Error: {{ error.message }}</div>
                   <CTableBody v-else-if="usersData">
                        <CTableRow v-for=" (item, index) in usersData" :key="item.id">
                            <CTableHeaderCell scope="row">{{ index +1  }}</CTableHeaderCell>
                            <CTableDataCell>{{ item.name }}</CTableDataCell>
                            <CTableDataCell>{{ item.email }}</CTableDataCell>
                            <CTableDataCell>{{ item.username }}</CTableDataCell>
                            <CTableDataCell>{{ item.role }}</CTableDataCell>
                            <CTableDataCell>
                                <CButton @click="userDeleteUser(item.id)"  color="danger" shape="rounded-pill">Delete</CButton>
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
import { ref , watch, computed} from 'vue'
import { useQuery, useMutation } from '@vue/apollo-composable'
import { gql } from 'graphql-tag'
import router from '@/router'
export default {
    setup() {
        const usersData = ref([]);
        const QUERY_USERS = gql`
            query {
                users{
                    id, name, username, email, role
                }
            }
        `;

        const DELETE_USERS = gql`
            mutation deleteUser($id: ID!) {
                deleteUser(id: $id)
            }
        `;

        const { result, loading, error, refetch }  = useQuery(QUERY_USERS);
        usersData.value = result?.value?.users;
        watch(result, value => {
            usersData.value = value?.users
        })

        const goToCreate = () => router.replace({name: 'create-users'})
        
        const { mutate: deleteUser } = useMutation(DELETE_USERS)
        const userDeleteUser = async (id) => {
            try {
                await deleteUser({id: id})
                usersData.value = usersData.value.filter((item) => item.id !== id);
                refetch();
            } catch (error) {
                console.error(error)
                alert('Error creating user')
            }
        }
       
        return {
            usersData,
            goToCreate,
            userDeleteUser,
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