#include <stdio.h>

int main(void)
{
	int number[10];
	int count = 10;
	long sum = 0L;
	float average = 0.0f;
	int i =0;

	printf("\nEnter the 10 numbers: \n");
	for (i = 0; i < count; i++) {
		printf("%2d> ", i + 1);
		scanf("%d", &number[i]);
		sum += number[i];	
	}

	for (i = 0; i < count; i++) {
		printf("\nGrade Number %d was %d", i + 1, number[i]);
	}

	average = (float)sum / count;
	printf("\nAverage of the ten numbers entered is : %f\n", average);
	return 0;
}
