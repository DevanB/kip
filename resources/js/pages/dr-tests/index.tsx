import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/app-layout';
import { index, show } from '@/routes/dr-tests';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/react';
import { PlusCircle } from 'lucide-react';
import { create } from '@/actions/App/Http/Controllers/DrTestController';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'DR Tests',
        href: index().url,
    },
];

interface DrTest {
    id: number;
    test_date: string;
    rto_minutes: number;
    rpo_minutes: number;
    phases_count: number;
}

interface IndexProps {
    drTests: DrTest[];
}

export default function Index({ drTests }: IndexProps) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="DR Tests" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <div className="flex items-center justify-between">
                    <h1 className="text-2xl font-bold">DR Test History</h1>
                    <Button asChild>
                        <Link href={create().url}>
                            <PlusCircle className="size-4" />
                            Add DR Test
                        </Link>
                    </Button>
                </div>
                <Card>
                    <CardHeader>
                        <CardTitle>All DR Tests</CardTitle>
                    </CardHeader>
                    <CardContent>
                        {drTests.length === 0 ? (
                            <p className="text-muted-foreground py-8 text-center">No DR tests recorded yet.</p>
                        ) : (
                            <Table>
                                <TableHeader>
                                    <TableRow>
                                        <TableHead>Test Date</TableHead>
                                        <TableHead className="text-right">RTO (min)</TableHead>
                                        <TableHead className="text-right">RPO (min)</TableHead>
                                        <TableHead className="text-right">Phases</TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    {drTests.map((test) => (
                                        <TableRow key={test.id} className="cursor-pointer hover:bg-muted/50">
                                            <TableCell className="font-medium">
                                                <Link href={show(test.id).url} className="hover:underline">
                                                    {test.test_date}
                                                </Link>
                                            </TableCell>
                                            <TableCell className="text-right">{test.rto_minutes}</TableCell>
                                            <TableCell className="text-right">{test.rpo_minutes}</TableCell>
                                            <TableCell className="text-right">{test.phases_count}</TableCell>
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
