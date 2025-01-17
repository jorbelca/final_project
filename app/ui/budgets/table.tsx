"use client";
import { fetchBudgets } from "@/app/lib/data";
import BudgetState from "@/app/ui/budgets/status";
import { useSession } from "next-auth/react";

export default function BudgetsTable({
  query,
  currentPage,
  budgets,
}: {
  query?: string;
  currentPage?: number;
  budgets: any;
}) {
  return (
    <div className="mt-6 flow-root">
      <div className="inline-block min-w-full align-middle">
        <div className="rounded-lg bg-gray-50 p-2 md:pt-0">
          <div className="md:hidden">
            {budgets?.map((budget) => (
              <div
                key={budget.budget_id}
                className="mb-2 w-full rounded-md bg-white p-4"
              >
                <div className="flex items-center justify-between border-b pb-4">
                  <div>
                    <div className="mb-2 flex items-center">
                      <p className="text-sm font-medium">{budget.budget_id}.</p>
                      &nbsp;
                      <p className="text-sm font-medium">
                        {budget.description}
                      </p>
                    </div>
                  </div>
                  <BudgetState status={budget.state} id={budget.budget_id} />
                </div>
                <div className="flex w-full items-center justify-between pt-4">
                  {JSON.stringify(budget.content)}

                  <p>{new Date(budget.created_at).toDateString()}</p>
                </div>
                {/* <div className="flex justify-end gap-2">
                    <UpdateInvoice id={budget.id} />
                    <DeleteInvoice id={budget.id} />
                  </div> */}
              </div>
            ))}
          </div>
          <table className="hidden min-w-full text-gray-900 md:table">
            <thead className="rounded-lg text-left text-sm font-normal">
              <tr>
                <th scope="col" className="px-4 py-5 font-medium sm:pl-6">
                  Id
                </th>
                <th scope="col" className="px-4 py-5 font-medium sm:pl-6">
                  Details
                </th>
                <th scope="col" className="px-4 py-5 font-medium sm:pl-6">
                  Discount
                </th>
                <th scope="col" className="px-4 py-5 font-medium sm:pl-6">
                  Taxes
                </th>
                <th scope="col" className="px-4 py-5 font-medium sm:pl-6">
                  Client
                </th>
                <th scope="col" className="px-3 py-5 font-medium">
                  Created
                </th>
                <th scope="col" className="px-3 py-5 font-medium">
                  Status
                </th>
                <th scope="col" className="relative py-3 pl-6 pr-3">
                  <span className="sr-only">Edit</span>
                </th>
              </tr>
            </thead>
            <tbody className="bg-white">
              {budgets?.map((budget) => (
                <tr key={budget.budget_id} className="border-b">
                  <td className="px-4 py-4">{budget.budget_id}</td>
                  <td className="px-4 py-4 w-full">
                    <div className="flex flex-col ">
                      {JSON.stringify(budget.content)}
                    </div>
                  </td>
                  <td className="px-6 py-4 ">{budget.discount}%</td>
                  <td className="px-6 py-4 ">{budget.taxes}%</td>
                  <td className="px-6 py-4 ">
                    {budget.client_id != null ? budget.name : ` ❌`}
                  </td>
                  <td className="px-3 py-4">
                    {budget.created_at.toDateString()}
                  </td>
                  <td className="px-3 py-4">
                    <BudgetState status={budget.state} id={budget.budget_id} />
                  </td>
                  <td className="px-3 py-4 text-right">
                    {/* <div className="flex justify-end gap-2">
                    <UpdateInvoice id={budget.id} />
                    <DeleteInvoice id={budget.id} />
                  </div> */}
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
}
