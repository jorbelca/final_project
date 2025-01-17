import { lusitana } from "@/app/ui/fonts";
import Search from "@/app/ui/search";
import { fetchClients } from "@/app/lib/actions";
import { PencilIcon, UserCircleIcon } from "@heroicons/react/24/outline";
import Image from "next/image";
import { Button } from "../button";
import Link from "next/link";
import DeleteBtn from "./delete-btn";
import { auth } from "@/auth";

export default async function ClientsTable() {
  const session = await auth();
  const clients = await fetchClients(Number(session?.user?.id));

  return (
    <div className="w-full">
      <div className="flex justify-between">
        <h1 className={`${lusitana.className} mb-8 text-xl md:text-2xl`}>
          Clients
        </h1>
        <Link href="/dashboard/clients/create">
          <Button>Add Client</Button>
        </Link>
      </div>
      {/* <Search placeholder="Search clients..." /> */}
      <div className="mt-6 flow-root">
        <div className="overflow-x-auto">
          <div className="inline-block min-w-full align-middle">
            <div className="overflow-hidden rounded-md bg-gray-50 p-2 md:pt-0">
              {/* Vista móvil */}
              <div className="md:hidden">
                {clients?.map((client) => (
                  <div
                    key={client.client_id}
                    className="mb-2 w-full rounded-md bg-white p-4"
                  >
                    <div className="flex items-center gap-3">
                      {client.image_url ? (
                        <Image
                          src={client.image_url.trimEnd()}
                          alt={client.name}
                          width={100}
                          height={100}
                          className="rounded-full"
                        />
                      ) : (
                        <UserCircleIcon className="w-6" />
                      )}
                      <div>
                        <p className="font-medium">{client.name}</p>
                        <p className="text-sm text-gray-500">{client.email}</p>
                      </div>
                      <div className="px-4 py-5">
                        <Link
                          href={`/dashboard/clients/edit/${client.client_id}`}
                        >
                          <Button>
                            <PencilIcon className="h-5 w-5" />
                          </Button>
                        </Link>
                        <br />

                        <DeleteBtn clientId={Number(client.client_id)} />
                      </div>
                    </div>
                  </div>
                ))}
              </div>
              {/* Vista en web */}
              <table className="hidden min-w-full rounded-md text-gray-900 md:table">
                <thead className="bg-gray-50 text-left text-sm font-medium">
                  <tr>
                    <th scope="col" className="px-4 py-5 sm:pl-6">
                      Image
                    </th>
                    <th scope="col" className="px-4 py-5">
                      Name
                    </th>
                    <th scope="col" className="px-4 py-5">
                      Email
                    </th>
                    <th scope="col" className="px-4 py-5">
                      Actions
                    </th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-gray-200">
                  {clients?.map((client) => (
                    <tr key={client.client_id} className="bg-white group">
                      <td className="px-4 py-5 sm:pl-6">
                        {client.image_url ? (
                          <Image
                            src={client.image_url.trimEnd()}
                            alt={client.name}
                            width={60}
                            height={60}
                            className="rounded-full"
                          />
                        ) : (
                          <UserCircleIcon className="w-6" />
                        )}
                      </td>
                      <td className="px-4 py-5 text-sm font-medium">
                        {client.name}
                      </td>
                      <td className="px-4 py-5 text-sm text-gray-500">
                        {client.email}
                      </td>
                      <td className="px-4 py-5">
                        <Link
                          href={`/dashboard/clients/edit/${client.client_id}`}
                        >
                          <Button>
                            <PencilIcon className="h-5 w-5" />
                          </Button>
                        </Link>
                        <br />
                        <DeleteBtn clientId={Number(client.client_id)} />
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
