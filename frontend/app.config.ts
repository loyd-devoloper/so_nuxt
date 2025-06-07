export default defineAppConfig({
  ui: {
    colors: {
      primary: '#ff0000',
  
     
    },
    modal:{
      slots:{
        overlay: 'fixed inset-0 bg-black/50 backdrop-blur-[4px]'
      }
    },
    formField:{
      slots:{
        error: "mt-[2px] bg-none text-xs"
      }
    }

  }
})