<template>
  <div class="wrapper min-vh-100 d-flex flex-row align-items-center">
    <CContainer>
      <CRow class="justify-content-center">
        <CCol :md="8">
          <CCardGroup>
            <CCard class="p-4">
              <CCardBody>
                <CForm novalidate
                      :validated="validatedLogin"
                      @submit.prevent="handleSubmit"
                >
                  <h1>Login</h1>
                  <p class="text-body-secondary">Sign In to your account</p>
                  <CInputGroup class="mb-3">
                    <CInputGroupText>
                      <CIcon icon="cil-user" />
                    </CInputGroupText>
                    <CFormInput
                      placeholder="Email"
                      autocomplete="email"
                      v-model="email"
                      required
                    />
                    <CFormFeedback invalid> Email in required. </CFormFeedback>
                  </CInputGroup>
                  <CInputGroup class="mb-4">
                    <CInputGroupText>
                      <CIcon icon="cil-lock-locked" />
                    </CInputGroupText>
                    <CFormInput
                      type="password"
                      placeholder="Password"
                       v-model="password"
                      autocomplete="current-password"
                      required
                    />
                    <CFormFeedback invalid> Passwords in required. </CFormFeedback>
                  </CInputGroup>
                  <CRow>
                    <CCol :xs="6">
                      <CButton type="submit" color="primary" class="px-4"> Login </CButton>
                    </CCol>
                    <!-- <CCol :xs="6" class="text-right">
                      <CButton color="link" class="px-0">
                        Forgot password?
                      </CButton>
                    </CCol> -->
                  </CRow>
                </CForm>
                <div v-if="loading">Loading...</div>
                <p v-if="error"><br/>
                  Error: {{ error.message }}
                </p>
              </CCardBody>
            </CCard>
          </CCardGroup>
        </CCol>
      </CRow>
    </CContainer>
  </div>
</template>
<script >
import { ref, computed } from 'vue'
import { useMutation, useQuery } from '@vue/apollo-composable'
import { gql } from 'graphql-tag'
import router from '@/router'
import { useAuthStore } from '@/stores/authStore';

export default {
  setup() {
    const validatedLogin = ref(false)
    const email = ref('')
    const password = ref('')
    const authStore = useAuthStore()

    const LOGIN_MUTATION = gql`
        mutation ($email: String!, $password: String!) {
          login(input: {email: $email, password: $password }) {
            token
            expires_at,
            user {
              name
            }
          }
        }
      `

      const { mutate: login, error, loading } = useMutation(LOGIN_MUTATION,
      () => ({ 
        variables: {
          email: email.value,
          password: password.value,
        },
      })
    );

    const handleSubmit = async (event) => {
      validateEmail(email.value);
      const form = event.currentTarget
      if (form.checkValidity() === false) {
        event.preventDefault()
        event.stopPropagation()
      }

      validatedLogin.value = true
      try {
        await login().then((data) => {
          localStorage.setItem('token', data.data.login.token)
          localStorage.setItem('user', data.data.login)
          authStore.setUserData(data.data.login)
        });

        // alert('User created successfully!')
        router.push('/dashboard')
      } catch (error) {
        console.error(error)
      }
    }

    const validateEmail = (email) => {
      const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
      return emailRegex.test(email);
    }

    return {
      email,
      password,
      handleSubmit,
      loading,
      error
    }


  },

}
</script>
<style scoped>
</style>
