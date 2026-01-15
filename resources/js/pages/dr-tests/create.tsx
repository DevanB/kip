import { store } from '@/actions/App/Http/Controllers/DrTestController';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/app-layout';
import { create } from '@/routes/dr-tests';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/react';
import { Plus, Trash2 } from 'lucide-react';
import { useMemo } from 'react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Add DR Test',
        href: create().url,
    },
];

interface Phase {
    title: string;
    started_at: string;
    finished_at: string;
}

function calculateDuration(startedAt: string, finishedAt: string): number | null {
    if (!startedAt || !finishedAt) {
        return null;
    }
    const start = new Date(startedAt);
    const end = new Date(finishedAt);
    const diffMs = end.getTime() - start.getTime();
    if (diffMs < 0) {
        return null;
    }
    return Math.floor(diffMs / (1000 * 60));
}

export default function Create() {
    const { data, setData, post, processing, errors } = useForm<{
        test_date: string;
        rto_minutes: string;
        rpo_minutes: string;
        notes: string;
        phases: Phase[];
    }>({
        test_date: '',
        rto_minutes: '',
        rpo_minutes: '',
        notes: '',
        phases: [{ title: '', started_at: '', finished_at: '' }],
    });

    const phaseDurations = useMemo(() => {
        return data.phases.map((phase) => calculateDuration(phase.started_at, phase.finished_at));
    }, [data.phases]);

    function addPhase() {
        setData('phases', [...data.phases, { title: '', started_at: '', finished_at: '' }]);
    }

    function removePhase(index: number) {
        if (data.phases.length > 1) {
            setData(
                'phases',
                data.phases.filter((_, i) => i !== index),
            );
        }
    }

    function updatePhase(index: number, field: keyof Phase, value: string) {
        const newPhases = [...data.phases];
        newPhases[index] = { ...newPhases[index], [field]: value };
        setData('phases', newPhases);
    }

    function handleSubmit(e: React.FormEvent) {
        e.preventDefault();
        post(store().url);
    }

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Add DR Test" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <h1 className="text-2xl font-bold">Add DR Test</h1>
                <form onSubmit={handleSubmit} className="space-y-6">
                    <Card className="max-w-2xl">
                        <CardHeader>
                            <CardTitle>DR Test Details</CardTitle>
                        </CardHeader>
                        <CardContent className="space-y-6">
                            <div className="grid gap-2">
                                <Label htmlFor="test_date">Test Date</Label>
                                <Input
                                    id="test_date"
                                    type="date"
                                    name="test_date"
                                    value={data.test_date}
                                    onChange={(e) => setData('test_date', e.target.value)}
                                    required
                                />
                                <InputError message={errors.test_date} />
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="rto_minutes">RTO (minutes)</Label>
                                <Input
                                    id="rto_minutes"
                                    type="number"
                                    name="rto_minutes"
                                    min="1"
                                    value={data.rto_minutes}
                                    onChange={(e) => setData('rto_minutes', e.target.value)}
                                    required
                                    placeholder="e.g., 45"
                                />
                                <InputError message={errors.rto_minutes} />
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="rpo_minutes">RPO (minutes)</Label>
                                <Input
                                    id="rpo_minutes"
                                    type="number"
                                    name="rpo_minutes"
                                    min="1"
                                    value={data.rpo_minutes}
                                    onChange={(e) => setData('rpo_minutes', e.target.value)}
                                    required
                                    placeholder="e.g., 30"
                                />
                                <InputError message={errors.rpo_minutes} />
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="notes">Notes (optional)</Label>
                                <textarea
                                    id="notes"
                                    name="notes"
                                    rows={4}
                                    value={data.notes}
                                    onChange={(e) => setData('notes', e.target.value)}
                                    className="border-input placeholder:text-muted-foreground focus-visible:border-ring focus-visible:ring-ring/50 flex w-full rounded-md border bg-transparent px-3 py-2 text-base shadow-xs outline-none focus-visible:ring-[3px] md:text-sm"
                                    placeholder="Add any additional notes about the DR test..."
                                />
                                <InputError message={errors.notes} />
                            </div>
                        </CardContent>
                    </Card>

                    <Card className="max-w-2xl">
                        <CardHeader className="flex flex-row items-center justify-between">
                            <CardTitle>Test Phases</CardTitle>
                            <Button type="button" variant="outline" size="sm" onClick={addPhase}>
                                <Plus className="mr-1 h-4 w-4" />
                                Add Phase
                            </Button>
                        </CardHeader>
                        <CardContent className="space-y-4">
                            <InputError message={errors.phases as string | undefined} />
                            {data.phases.map((phase, index) => (
                                <div key={index} className="rounded-lg border p-4">
                                    <div className="mb-4 flex items-center justify-between">
                                        <span className="text-sm font-medium">Phase {index + 1}</span>
                                        {data.phases.length > 1 && (
                                            <Button type="button" variant="ghost" size="sm" onClick={() => removePhase(index)}>
                                                <Trash2 className="h-4 w-4 text-destructive" />
                                            </Button>
                                        )}
                                    </div>
                                    <div className="grid gap-4">
                                        <div className="grid gap-2">
                                            <Label htmlFor={`phases.${index}.title`}>Title</Label>
                                            <Input
                                                id={`phases.${index}.title`}
                                                name={`phases[${index}][title]`}
                                                value={phase.title}
                                                onChange={(e) => updatePhase(index, 'title', e.target.value)}
                                                placeholder="e.g., Failover initiation"
                                                required
                                            />
                                            <InputError message={(errors as Record<string, string>)[`phases.${index}.title`]} />
                                        </div>
                                        <div className="grid grid-cols-2 gap-4">
                                            <div className="grid gap-2">
                                                <Label htmlFor={`phases.${index}.started_at`}>Start Time</Label>
                                                <Input
                                                    id={`phases.${index}.started_at`}
                                                    name={`phases[${index}][started_at]`}
                                                    type="datetime-local"
                                                    value={phase.started_at}
                                                    onChange={(e) => updatePhase(index, 'started_at', e.target.value)}
                                                    required
                                                />
                                                <InputError message={(errors as Record<string, string>)[`phases.${index}.started_at`]} />
                                            </div>
                                            <div className="grid gap-2">
                                                <Label htmlFor={`phases.${index}.finished_at`}>End Time</Label>
                                                <Input
                                                    id={`phases.${index}.finished_at`}
                                                    name={`phases[${index}][finished_at]`}
                                                    type="datetime-local"
                                                    value={phase.finished_at}
                                                    onChange={(e) => updatePhase(index, 'finished_at', e.target.value)}
                                                    required
                                                />
                                                <InputError message={(errors as Record<string, string>)[`phases.${index}.finished_at`]} />
                                            </div>
                                        </div>
                                        {phaseDurations[index] !== null && (
                                            <div className="text-muted-foreground text-sm">
                                                Duration: <span className="font-medium">{phaseDurations[index]} minutes</span>
                                            </div>
                                        )}
                                    </div>
                                </div>
                            ))}
                        </CardContent>
                    </Card>

                    <div className="flex items-center gap-4">
                        <Button type="submit" disabled={processing}>
                            {processing ? 'Saving...' : 'Save DR Test'}
                        </Button>
                    </div>
                </form>
            </div>
        </AppLayout>
    );
}
