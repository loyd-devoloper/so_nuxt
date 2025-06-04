<template>
  <section class="h-screen flex justify-center items-center">
    <form class="flex flex-col min-w-[100swv] h-fit gap-4 items-center"  @submit.prevent="submitOtp()">
      <UCard variant="outline" class=" max-w-md mx-auto">

        <NuxtImg src="/LOGOONLY.svg" width="70" class="mx-auto"/>
        <h1 class="text-center font-bold">Email Address Verification</h1>
        <h2 class="text-center ">Enter the 6-digit verification code that was sent to your email address.</h2>


        <main class="space-y-2 mt-8">
          <UFormField name="otp" >
            <UPinInput v-model="otp" required  placeholder="○" length="6" :required="false" size="lg" class="w-full flex justify-evenly" type="number"/>
          </UFormField>

          <p v-if="error?.status == 401" class="text-red-500 text-center">{{ error.data.errors?.otp[0] }}</p>
          <p v-if="isExpired" class="text-center">OTP has expired.</p>
          <p v-else class="mt-2 text-center opacity-65">
            {{ remainingTime.minutes }}:{{ remainingTime.seconds }}
          </p>
          <button
                  v-show="!isPending"
                  class="mt-5 text-blue-700 hover:underline disabled:hover:no-underline disabled:opacity-45 disabled:cursor-not-allowed"
                  :disabled="!isExpired" type="button"
                  @click="resendOtpFunc()">resend</button>
        </main>
        <UButton
            color="primary" size="lg" type="submit" class="w-full mt-12 flex justify-center" :disabled="isPendingSubmit">
          <UIcon v-if="isPendingSubmit" name="mingcute:loading-line" class="animate-spin"/>
          Submit
        </UButton>
      </UCard>
      <div class="flex items-center justify-center ">
        <NuxtLink to="/" >
          <Button variant="link" class="flex items-center justify-center text-black font-medium">
            <UIcon name="fluent:ios-arrow-24-filled"/>
            <span>Go back to Sign in</span>
          </Button>
        </NuxtLink>
      </div>
    </form>

  </section>
</template>
<script setup lang="ts">
import moment from 'moment'
import {useAuthStore} from "~/stores/AuthStore";
import {useMutation, useQueryClient} from "@tanstack/vue-query";
import type {RemainingTime} from "~/shared/types/LoginType";

const authStore = useAuthStore();
const route = useRoute();
const queryClient = useQueryClient();
const toast = useToast()
const {mutate:fetcOtp,isPending} = useMutation({
  mutationFn: async () =>await authStore.getOtpInfo(route?.params?.token ?? ''),
  onSuccess:(data) =>{
    startTimer(data)
  }
})


const otp = ref<string[]>([]);
const { mutate: submitOtp, error, isPending: isPendingSubmit} = useMutation({
  mutationFn: () => authStore.otpVerification(otp, route.params.token || ''),
  onSuccess:  (data) => {
     localStorage.setItem("token", data.token);
     localStorage.setItem("role", data.role);

   return  navigateTo({name:'Qad-Dashboard'});
  },
  onError: (err: any) => {

    toast.add({
      title:err.otp[0],
      color: 'error',
      icon:'material-symbols:error-outline'
    })
  }
})

const { mutate: resendOtpFunc } = useMutation({
  mutationFn: () =>authStore.resendOtp(route.params.token || ''),
  onSuccess: () => {
    isExpired.value = false;
    fetcOtp()
    toast.add({
      title: 'OTP resent successfully',
      color: 'success',
    })
  },

})
const timer = ref<any>(0);
const remainingTime = ref<RemainingTime>({minutes: 0, seconds: 0});
const isExpired = ref<boolean>(false);

const calculateRemainingTime = (date: string) => {

  const expirationTime = moment(date);
  const currentTime = moment();
  const diffSec = currentTime.diff(expirationTime, 'seconds');

  const expirationTimeSec  = (10 * 60) - diffSec;

  if (expirationTimeSec > 0) {

    remainingTime.value = {
      minutes: Math.floor(expirationTimeSec / 60),
      seconds: Math.floor(expirationTimeSec % 60),
    };
  } else {
    isExpired.value = true;
    remainingTime.value = {minutes: 0, seconds: 0};
  }
};

const startTimer =  (date:string) => {
  calculateRemainingTime(date)

  timer.value = setInterval(() => {
    calculateRemainingTime(date)
    if (isExpired.value) {
      clearInterval(timer.value!);

    }

  }, 1000);
};
onMounted( () =>{
   fetcOtp()
})
onBeforeUnmount(() => {
  if (timer.value) {
    clearInterval(timer.value);
  }
});
</script>