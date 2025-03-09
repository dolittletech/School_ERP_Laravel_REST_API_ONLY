import { toastHandler } from "../../../../utils/functions";
import { apiSlice } from "../api/apiSlice";

export const BackendThemeApi = apiSlice.injectEndpoints({
  endpoints: (builder) => ({
    updateTheme: builder.mutation({
      query: (themeData) => ({
        url: "setting/general-setting/update-theme",
        method: "POST",
        body: themeData,
      }),
      async onQueryStarted(arg, { queryFulfilled }) {
        try {
          const { data } = await queryFulfilled;
          toastHandler(data.message, "success");
        } catch (err) {
          toastHandler("Failed to update theme, please try again", "warning");
        }
      },
      invalidatesTags: ["setting/general-setting/update-theme"],
    }),
  }),
});

export const { useUpdateThemeMutation } = BackendThemeApi;