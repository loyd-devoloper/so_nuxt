export default defineAppConfig({
  ui: {
    colors: {
      primary: 'red',
     
    },
    modal:{
      slots:{
        overlay: 'fixed inset-0 bg-black/50 backdrop-blur-[4px]'
      }
    }
  }
})