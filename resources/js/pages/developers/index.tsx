import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/app-layout';
import { index } from '@/routes/developers';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/react';
import { PlusCircle } from 'lucide-react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Developers',
        href: index().url,
    },
];

interface Developer {
    id: number;
    name: string;
    email: string;
    github_username: string | null;
    gitlab_username: string | null;
}

interface IndexProps {
    developers: Developer[];
}

export default function Index({ developers }: IndexProps) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Developers" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <div className="flex items-center justify-between">
                    <h1 className="text-2xl font-bold">Developers</h1>
                    <Button asChild>
                        <Link href="/developers/create">
                            <PlusCircle className="size-4" />
                            Add Developer
                        </Link>
                    </Button>
                </div>
                <Card>
                    <CardHeader>
                        <CardTitle>Developer Directory</CardTitle>
                    </CardHeader>
                    <CardContent>
                        {developers.length === 0 ? (
                            <p className="text-muted-foreground py-8 text-center">No developers added yet.</p>
                        ) : (
                            <Table>
                                <TableHeader>
                                    <TableRow>
                                        <TableHead>Name</TableHead>
                                        <TableHead>Email</TableHead>
                                        <TableHead>GitHub</TableHead>
                                        <TableHead>GitLab</TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    {developers.map((developer) => (
                                        <TableRow key={developer.id} className="hover:bg-muted/50">
                                            <TableCell className="font-medium">{developer.name}</TableCell>
                                            <TableCell>{developer.email}</TableCell>
                                            <TableCell>{developer.github_username || '-'}</TableCell>
                                            <TableCell>{developer.gitlab_username || '-'}</TableCell>
                                        </TableRow>
                                    ))}
                                </TableBody>
                            </Table>
                        )}
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}
