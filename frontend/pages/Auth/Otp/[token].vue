<template>
  <section class="h-screen flex justify-center items-center">
    <form class="flex flex-col min-w-[100swv] h-fit gap-4 items-center"  @submit.prevent="submitOtp()">
      <UCard variant="outline" class=" max-w-md mx-auto">

        <NuxtImg src="/LOGOONLY.svg" width="70" class="mx-auto"/>
        <h1 class="text-center font-bold">Email Address Verification</h1>
        <h2 class="text-center ">Enter the 6-digit verification code that was sent to your email address.</h2>


        <main class="space-y-2 mt-8">
          <UFormField name="email" required>
            <UPinInput v-model="otp" placeholder="○" length="6" :required="false" size="lg" class="w-full flex justify-evenly" type="number"/>


          </UFormField>

          <p v-if="error?.status == 401" class="text-red-500 text-center">{{ error.data.errors?.otp[0] }}</p>
          <p v-if="isExpired" class="text-center">OTP has expired.</p>
          <p v-else class="mt-2 text-center opacity-65">
            {{ remainingTime.minutes }}:{{ remainingTime.seconds }}
          </p>
          <button
                  v-show="!isLoading"
                  class="mt-5 text-blue-700 hover:underline disabled:hover:no-underline disabled:opacity-45 disabled:cursor-not-allowed"
                  :disabled="remainingTime.minutes != 0 && remainingTime.seconds != 0" type="button"
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
const {data: otpData, isLoading} = useQuery({
  queryKey: ['otpInfo'],
  queryFn: () => authStore.getOtpInfo(route?.params?.token ?? '')
})
const otp = ref<string[]>([]);
const { mutate: submitOtp, error, isPending: isPendingSubmit} = useMutation({
  mutationFn: () => authStore.otpVerification(otp, route.params.token || ''),
  onSuccess: async (data) => {

    await localStorage.setItem("token", data.token);
    await localStorage.setItem("role", data.user?.role.role_name);
    if (data.user.role.role_name === 'qad') return navigateTo({name: 'Qad-Dashboard'})
    //
    // {
    //
    //
    // } else if (data.user.role.role_name == 'sdo') {
    //
    //
    //   authStore.code = RoleEnum.SDO
    //   await router.push({ name: 'SDO Dashboard' })
    // } else {
    //
    //
    //   authStore.code = RoleEnum.SCHOOL;
    //   await router.push({ name: "Dashboard" });
    // }

  },
  onError: (err: any) => {
    console.log(err)
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
    queryClient.invalidateQueries({ queryKey: ['otpInfo'] })

    toast.add({
      title: 'OTP resent successfully',
      color: 'success',
    })
  }
})
const timer = ref<number>(0);
const remainingTime = ref<RemainingTime>({minutes: 0, seconds: 0});

const isExpired = computed(() => {
  return moment().isAfter(moment(otpData.value?.otp_expired_at));
});

const calculateRemainingTime = () => {
  const expirationTime = moment(otpData.value?.otp_expired_at);
  const currentTime = moment();
  const duration = moment.duration(expirationTime.diff(currentTime));

  if (duration.asMilliseconds() > 0) {
    remainingTime.value = {
      minutes: Math.floor(duration.asMinutes()),
      seconds: Math.floor(duration.asSeconds() % 60),
    };
  } else {
    remainingTime.value = {minutes: 0, seconds: 0};
  }
};

const startTimer = () => {
  calculateRemainingTime();
  timer.value = setInterval(() => {
    calculateRemainingTime();
    if (isExpired.value) {
      clearInterval(timer.value!);
    }
  }, 1000);
};

onMounted(() => {
  startTimer();


});

onBeforeUnmount(() => {
  if (timer.value) {
    clearInterval(timer.value);
  }
});
</script>