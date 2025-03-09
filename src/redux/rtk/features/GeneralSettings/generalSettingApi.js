import { toastHandler } from "../../../../utils/functions";
import { apiSlice } from "../api/apiSlice";

export const generalSettingApi = apiSlice.injectEndpoints({
  endpoints: (builder) => ({
    // Fetch General Settings
    getGeneralSettings: builder.query({
      query: () => ({
        url: `setting/general-setting`, // Matches your Laravel route
      }),
      providesTags: ["generalSetting"], // Use a unique tag for general settings
    }),

    // Update General Settings
    updateGeneralSettings: builder.mutation({
      query: (values) => ({
        method: "POST",
        url: `setting/general-setting`, // Matches your Laravel route
        body: values,
      }),
      async onQueryStarted(arg, { queryFulfilled, dispatch }) {
        try {
          await queryFulfilled;
          toastHandler("General Settings updated successfully", "success");
        } catch (err) {
          toastHandler("Something went wrong, Please try again", "warning");
        }
      },
      invalidatesTags: ["generalSetting"], // Invalidate cache on update
    }),
  }),
});

export const { useGetGeneralSettingsQuery, useUpdateGeneralSettingsMutation } = generalSettingApi;