import Table from "@/app/ui/budgets/table";

import { lusitana } from "@/app/ui/fonts";
import { InvoicesTableSkeleton } from "@/app/ui/skeletons";
import { Suspense } from "react";
import { fetchBudgets } from "@/app/lib/data";
import { Metadata } from "next";
import { auth } from "@/auth";
export const dynamic = "force-dynamic";
export const dynamicParams = true;

export const metadata: Metadata = {
  title: "Budgets",
};

export default async function Page(props: {
  searchParams?: Promise<{
    query?: string;
    page?: string;
  }>;
}) {
  const searchParams = await props.searchParams;
  const query = searchParams?.query || "";
  const currentPage = Number(searchParams?.page) || 1;
  //const totalPages = await fetchBudgets();
  const session = await auth();
  console.log(session?.user);

  const budgets = await fetchBudgets(Number(session?.user?.id));
  return (
    <div className="w-full">
      <div className="flex w-full items-center justify-between">
        <h1 className={`${lusitana.className} text-2xl`}>Budgets</h1>
      </div>
      <div className="mt-4 flex items-center justify-between gap-2 md:mt-8">
        {/* <Search placeholder="Search budgets..." /> */}
        {/* <CreateInvoice /> */}
      </div>
      <Suspense key={query + currentPage} fallback={<InvoicesTableSkeleton />}>
        <Table budgets={budgets} />
      </Suspense>
      <div className="mt-5 flex w-full justify-center">
        {/* <Pagination totalPages={totalPages.totalPages} /> */}
      </div>
    </div>
  );
}
