#include <stdio.h>

int main(void)
{
	int number = 0;
	int count = 10;
	long sum = 0L;
	float average = 0.0f;
	
	int i = 0;
	for (i = 0; i< count; i++) {
		printf("Enter grade: ");
		scanf("%d", &number);
		sum += number;	
	}

	average = (float)sum/count;

	printf("\nAverage of the ten numbers entered is : %f\n", average);
	return 0;
}
