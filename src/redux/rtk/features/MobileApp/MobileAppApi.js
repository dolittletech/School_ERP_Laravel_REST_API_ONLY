import { toastHandler } from "../../../../utils/functions";
import { apiSlice } from "../api/apiSlice";


export const MobileAppApi = apiSlice.injectEndpoints({
  endpoints: (builder) => ({
    updateMobileApp: builder.mutation({
      query: (formData) => ({
        url: "setting/general-setting/update-mobile-app",
        method: "POST",
        body: formData,
      }),
      async onQueryStarted(arg, { queryFulfilled }) {
        try {
          const { data } = await queryFulfilled;
          toastHandler(data.message, "success");
        } catch (err) {
          toastHandler("Failed to update mobile app settings, please try again", "warning");
        }
      },
      invalidatesTags: ["setting/general-setting/update-mobile-app"],
    }),
    registerAndroidApp: builder.mutation({
      query: (formData) => ({
        url: "setting/general-setting/register-android-app",
        method: "POST",
        body: formData,
      }),
      async onQueryStarted(arg, { queryFulfilled }) {
        try {
          const { data } = await queryFulfilled;
          toastHandler(data.message, "success");
        } catch (err) {
          toastHandler("Failed to register Android app, please try again", "warning");
        }
      },
      invalidatesTags: ["setting/general-setting/register-android-app"],
    }),
  }),
});

export const { useUpdateMobileAppMutation, useRegisterAndroidAppMutation } = MobileAppApi;