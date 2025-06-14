<template>
  <form class="flex min-w-[100swv]  min-h-screen justify-between items-center" @submit.prevent="loginFunc()" >
    <UCard variant="outline" class="min-w-sm max-w-sm mx-auto">

      <NuxtImg src="/LOGOONLY.svg" width="70" class="mx-auto"/>
      <h2 class="text-center font-medium">Sign in to your account</h2>
      <!--input container  -->

      <main class="space-y-2 mt-4">
        <div>
          <UFormField label="Email" name="email" :error="error?.email && error?.email[0]" >
            <UInput  v-model="credentials.email" size="lg" class="w-full m-0" variant="outline" type="email" placeholder="Email Address..."
            />

          </UFormField>

        </div>
        <div>

          <UFormField label="Password"  name="password" :error="error?.password && error?.password[0]">
            <UInput
                v-model="credentials.password" size="lg" class="w-full" variant="outline"
                placeholder="Password" :type="show ? 'text' : 'password'">
              <template #trailing>
                <UButton
                    color="neutral" variant="link" size="sm" :icon="show ? 'i-lucide-eye-off' : 'i-lucide-eye'"
                    :aria-label="show ? 'Hide password' : 'Show password'" :aria-pressed="show"
                    aria-controls="password"
                    @click="show = !show"/>
              </template>
            </UInput>

          </UFormField>
        </div>


      </main>
      <UButton
          color="primary" size="lg" type="submit" class="w-full mt-4 flex justify-center" :disabled="isPending">
        <UIcon v-if="isPending" name="mingcute:loading-line" class="animate-spin"/>
        Login</UButton>
    </UCard>
  </form>

</template>
<script setup lang="ts">
import {useAuthStore} from "~/stores/AuthStore";
import {useMutation} from "@tanstack/vue-query";
import type {CredentialType} from "~/shared/types/LoginType";
const authStore = useAuthStore();
const show = ref(false)

const credentials = reactive<CredentialType>({
  email: "",
  password: "",
})


// QAD TEAHUB LOGIN FUNCTION

const {error,mutate:loginFunc,isPending} = useMutation({
  mutationFn: () => authStore.teahubLogin(credentials),
  onSuccess: (data) => {

    if(data.status === 201)
    {
      localStorage.setItem("token",  data.data.token);
      localStorage.setItem("role",  data.data.role);
      navigateTo({name:'Qad-Dashboard'})
    }else{
      navigateTo({name: 'Auth-Otp-token', params: { 'token' : data.data.token}});
    }

  },
  // eslint-disable-next-line @typescript-eslint/no-explicit-any
  onError: (_error : any) => {
  
      
  }
})

</script>