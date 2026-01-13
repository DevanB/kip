import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';

interface RtoWidgetProps {
    latestRto: number | null;
    targetRto: number;
}

function getStatusColor(current: number, target: number): string {
    if (current <= target) {
        return 'bg-green-500';
    }
    const percentAbove = ((current - target) / target) * 100;
    if (percentAbove <= 50) {
        return 'bg-yellow-500';
    }
    return 'bg-red-500';
}

function getProgressPercentage(current: number, target: number): number {
    if (current <= target) {
        return 100;
    }
    const percentAbove = ((current - target) / target) * 100;
    return Math.max(0, 100 - percentAbove);
}

export function RtoWidget({ latestRto, targetRto }: RtoWidgetProps) {
    const hasData = latestRto !== null;
    const isAchieved = hasData && latestRto <= targetRto;
    const statusColor = hasData ? getStatusColor(latestRto, targetRto) : 'bg-muted';
    const progressPercentage = hasData ? getProgressPercentage(latestRto, targetRto) : 0;

    return (
        <Card className="h-full">
            <CardHeader className="pb-2">
                <CardDescription>Recovery Time Objective</CardDescription>
                <CardTitle className="text-4xl">{hasData ? `${latestRto} min` : 'No data'}</CardTitle>
            </CardHeader>
            <CardContent className="space-y-4">
                <div className="text-sm text-muted-foreground">Target: {targetRto} minutes</div>
                <div className="space-y-2">
                    <div className="h-3 w-full overflow-hidden rounded-full bg-muted">
                        <div className={`h-full transition-all ${statusColor}`} style={{ width: `${progressPercentage}%` }} />
                    </div>
                    {isAchieved && <div className="text-sm font-medium text-green-600 dark:text-green-400">Target achieved!</div>}
                    {hasData && !isAchieved && <div className="text-sm text-muted-foreground">{latestRto - targetRto} minutes over target</div>}
                </div>
            </CardContent>
        </Card>
    );
}
