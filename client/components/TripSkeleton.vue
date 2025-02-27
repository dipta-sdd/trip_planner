<template>
    <div class="bg-gray-100 p-8 capitalize">
        <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-lg p-8">

            <!-- Trip Title -->
            <div class="flex justify-between items-center mb-4 animate-pulse">
                <div class="w-3/4 h-6 bg-gray-300 rounded"></div>
                <div class="w-24 h-8 bg-gray-300 rounded"></div>
            </div>

            <!-- Trip Description -->
            <div class="flex justify-between items-center mb-6 animate-pulse">
                <div class="w-2/3 h-4 bg-gray-300 rounded"></div>
            </div>

            <!-- Trip Details Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-8 animate-pulse">

                <!-- Start Date -->
                <div class="w-1/2">
                    <div class="h-4 bg-gray-300 rounded mb-2"></div>
                    <div class="h-4 bg-gray-300 rounded"></div>
                </div>

                <!-- End Date -->
                <div class="w-1/2">
                    <div class="h-4 bg-gray-300 rounded mb-2"></div>
                    <div class="h-4 bg-gray-300 rounded"></div>
                </div>

                <!-- Status -->
                <div class="w-1/2">
                    <div class="h-4 bg-gray-300 rounded mb-2"></div>
                    <div class="h-4 bg-gray-300 rounded"></div>
                </div>

                <!-- Budget -->
                <div class="w-1/2">
                    <div class="h-4 bg-gray-300 rounded mb-2"></div>
                    <div class="h-4 bg-gray-300 rounded"></div>
                </div>

            </div>

            <!-- Participants Section -->
            <div class="mb-8 animate-pulse">
                <div class="flex justify-between items-center mb-4">
                    <div class="w-2/3 h-6 bg-gray-300 rounded"></div>
                    <div class="w-24 h-8 bg-gray-300 rounded"></div>
                </div>
                <div class="bg-gray-50 p-3 rounded-lg">
                    <div class="space-y-4">
                        <!-- Participant Placeholder -->
                        <div class="bg-white p-4 rounded-lg shadow-sm animate-pulse">
                            <div class="flex justify-between items-center">
                                <div class="w-2/3 h-4 bg-gray-300 rounded"></div>
                                <div class="w-12 h-4 bg-gray-300 rounded"></div>
                            </div>
                        </div>
                        <div class="bg-white p-4 rounded-lg shadow-sm animate-pulse">
                            <div class="flex justify-between items-center">
                                <div class="w-2/3 h-4 bg-gray-300 rounded"></div>
                                <div class="w-12 h-4 bg-gray-300 rounded"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sponsors Section -->
            <div class="mb-8 animate-pulse">
                <div class="flex justify-between items-center mb-4">
                    <div class="w-2/3 h-6 bg-gray-300 rounded"></div>
                    <div class="w-24 h-8 bg-gray-300 rounded"></div>
                </div>
                <div class="bg-gray-50 p-3 rounded-lg">
                    <div class="space-y-4">
                        <!-- Sponsor Placeholder -->
                        <div class="bg-white p-4 rounded-lg shadow-sm relative animate-pulse">
                            <div class="flex justify-between items-center mb-2">
                                <div class="w-3/4 h-4 bg-gray-300 rounded"></div>
                                <div class="w-16 h-6 bg-gray-300 rounded"></div>
                            </div>
                            <div class="h-4 bg-gray-300 rounded"></div>
                        </div>
                        <div class="bg-white p-4 rounded-lg shadow-sm relative animate-pulse">
                            <div class="flex justify-between items-center mb-2">
                                <div class="w-3/4 h-4 bg-gray-300 rounded"></div>
                                <div class="w-16 h-6 bg-gray-300 rounded"></div>
                            </div>
                            <div class="h-4 bg-gray-300 rounded"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Itinerary Section -->
            <div class="mb-8 animate-pulse">
                <div class="flex justify-between items-center mb-4">
                    <div class="w-2/3 h-6 bg-gray-300 rounded"></div>
                    <div class="w-24 h-8 bg-gray-300 rounded"></div>
                </div>
                <div class="space-y-6">
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <div class="h-4 bg-gray-300 rounded mb-2"></div>
                        <div class="h-4 bg-gray-300 rounded"></div>
                    </div>
                </div>
            </div>

            <!-- Expense Summary Section -->
            <div class="mb-8 animate-pulse">
                <div class="flex justify-between items-center mb-4">
                    <div class="w-2/3 h-6 bg-gray-300 rounded"></div>
                    <div class="w-24 h-8 bg-gray-300 rounded"></div>
                </div>
                <div class="bg-gray-50 p-3 rounded-lg">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="h-4 bg-gray-300 rounded mb-2"></div>
                        <div class="h-4 bg-gray-300 rounded mb-2"></div>
                        <div class="h-4 bg-gray-300 rounded"></div>
                    </div>
                </div>
            </div>

            <!-- Detailed Expenses Section -->
            <div class="mb-8 animate-pulse">
                <div class="flex justify-between items-center mb-4">
                    <div class="w-2/3 h-6 bg-gray-300 rounded"></div>
                    <div class="w-24 h-8 bg-gray-300 rounded"></div>
                </div>
                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    <div class="w-10 h-4 bg-gray-300 rounded mx-auto"></div>
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    <div class="w-10 h-4 bg-gray-300 rounded mx-auto"></div>
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    <div class="w-10 h-4 bg-gray-300 rounded mx-auto"></div>
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                    <div class="w-10 h-4 bg-gray-300 rounded mx-auto"></div>
                                </th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                                    <div class="w-10 h-4 bg-gray-300 rounded mx-auto"></div>
                                </th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">
                                    <div class="w-10 h-4 bg-gray-300 rounded mx-auto"></div>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr>
                                <td colspan="5" class="px-4 py-4 text-center text-gray-500">
                                    <div class="w-100 h-4 bg-gray-300 rounded mx-auto"></div>
                                </td>
                            </tr>
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="4" class="px-4 py-3 text-right text-sm font-medium">
                                    <div class="w-20 h-4 bg-gray-300 rounded ms-auto"></div>
                                </td>
                                <td class="px-4 py-3 text-right text-sm font-medium">
                                    <div class="w-20 h-4 bg-gray-300 rounded me-auto"></div>
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

        </div>
    </div>
</template>
