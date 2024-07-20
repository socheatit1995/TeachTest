<template>
  <CRow>
    <CCol :xs="12">
      <CCard class="mb-4">
        <CCardHeader>
          <strong>USER</strong>
        </CCardHeader>
        <CCardBody>
              <CForm @submit.prevent="handleSubmit" class="p-4">
                <h1>Create User</h1>
                <!-- <p class="text-body-secondary">Create</p> -->
                <CInputGroup class="mb-3">
                  <CFormInput placeholder="name" v-model="name" autocomplete="name" required/>
                </CInputGroup>
                <CInputGroup class="mb-3">
                  <CFormInput placeholder="Email" v-model="email" autocomplete="email" required/>
                </CInputGroup>
                <CInputGroup class="mb-3">
                  <CFormInput placeholder="UserName" v-model="username"  autocomplete="username" required />
                </CInputGroup>
                <CInputGroup class="mb-3">
                  <CFormSelect aria-label="Default select example" v-model="role" >
                    <option value="admin">Admin</option>
                    <option value="user">User</option>
                  </CFormSelect>
                </CInputGroup>
                <CInputGroup class="mb-3">
                  <CFormInput
                    v-model="password" 
                    type="password"
                    placeholder="Password"
                    autocomplete="new-password"
                    required
                  />
                </CInputGroup>
                <CInputGroup class="mb-4">
                  <CFormInput
                    v-model="confirmPassword"
                    type="password"
                    placeholder="Repeat password"
                    autocomplete="new-password"
                    required
                  />
                </CInputGroup>
                <div class="text-rigth">
                  <CButton type="submit" color="primary" shape="rounded-pill"> Save </CButton>
                </div>
              </CForm>
              <div v-if="loading">Loading...</div>
              <div v-if="error">Error: {{ error.message }}</div>
            </CCardBody>
          </CCard>
        </CCol>
      </CRow>
</template>
<script>
import { ref } from 'vue'
import { useMutation } from '@vue/apollo-composable'
import gql from 'graphql-tag'
import router from '@/router'

export default {
  setup () {
    const name = ref('')
    const email = ref('')
    const username = ref('')
    const password = ref('')
    const confirmPassword = ref('')
    const role = ref('admin')

    const createUserMutation = gql`
      mutation createUser (
        $name: String!
        $email: String!
        $username: String!
        $password: String!
        $role: String!
      ) {
        createUser (
          name: $name
          email: $email
          username: $username
          password: $password
          role: $role
        ) {
          id
          name
          email
          username
          role
        }
      }
    `;

    const { mutate: createUser, error, loading } = useMutation(createUserMutation,
      () => ({ 
        variables: {
          name: name.value,
          email: email.value,
          username: username.value,
          password: password.value,
          role: role.value,
        },
      })
    )

    const handleSubmit = async () => {
      if (password.value !== confirmPassword.value) {
        alert('Passwords do not match')
        return
      }
      const inpuForm = {
            name: name.value,
            email: email.value,
            username: username.value,
            password: password.value,
            role: role.value,
          }
      console.log('form',inpuForm);
      try {
        await createUser();
        alert('User created successfully!')
        router.push('/users')
      } catch (error) {
        console.error(error)
        alert('Error creating user')
      }
    }
    
    return {
      name,
      email,
      username,
      password,
      confirmPassword,
      role,
      handleSubmit,
      error,
      loading,
    }
  },
}
</script>
